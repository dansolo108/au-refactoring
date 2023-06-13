<?php
/** @var modX $modx */
/** @var array $scriptProperties */

$details = $modx->getOption('details', $scriptProperties, []);
if(is_string($details)){
    $details = explode(',',$details);
}
/** @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
$path = $modx->getOption('pdofetch_class_path', null, MODX_CORE_PATH . 'components/pdotools/model/', true);
if ($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoFetch = new $pdoClass($modx, $scriptProperties);
} else {
    return false;
}
$where = [
    'Modification.hide'=>0,
    'msProduct.deleted'=>0,
    'msProduct.published'=>1,
];

if(!empty($scriptProperties["where"])){
    $where = array_merge($where,$scriptProperties["where"]);
    unset($scriptProperties["where"]);
}

$default = [
    'class'=>'msProduct',
    'innerJoin'=>[
        'Modification'=>[
            'class'=>'Modification',
            'on'=>'Modification.product_id = msProduct.id',
        ],
    ],
    'parents'=>$parents?:0,
    "leftJoin"=>[
        'Data'=>[
            'class'=>'msProductData',
            'on'=>'msProduct.id = Data.id',
        ],
        'Vendor' => [
            'class' =>'msVendor',
            'on' => 'Data.vendor=Vendor.id'
        ],
    ],
    'select'=>[
        'msProduct' => "*",
        'Data' => $modx->getSelectColumns('msProductData', 'Data','',['id'],true),
        'Vendor' => 'Vendor.name as vendor',
    ],
    'groupby' => "msProduct.id",
    'limit'=> $limit?:10,
    "where"=>$where,
    'return'=>'data',
    'polyLang'=>false,
];
$pdoFetch->setConfig($default);
$products = $pdoFetch->run();
$leftJoin = [];
$select = [
    'Modification' => "*",
];
foreach($details as $key=>$detail){
    /** @var DetailType $detailType */
    $detailType =  $modx->getObject('DetailType',['name'=>$detail]);
    if($detailType){
        $detailTable = 'Detail'.ucfirst($detail);
        $leftJoin[$detailTable]= [
            'class'=>'ModificationDetail',
            'on'=>"Modification.id = {$detailTable}.modification_id AND {$detailTable}.type_id = '{$detailType->get('id')}'"
        ];
        $select[$detailTable] = $detailTable.'.value as '.$detail;
        foreach (['where','having'] as $item) {
            if(!empty($scriptProperties[$item]))
                foreach ($scriptProperties[$item] as $key => $value) {
                    $tmp = explode(':', $key);
                    switch (count($tmp)) {
                        case 3:
                            $varName = $tmp[1];
                            break;
                        case 2:
                            if ($tmp[0] === 'AND' || $tmp[0] === 'OR') {
                                $varName = $tmp[1];
                                break;
                            }
                        default:
                            $varName = $tmp[0];
                            break;
                    }
                    if ($varName === $detail) {
                        $scriptProperties[$item][str_replace($detail, $detailTable . '.value', $key)] = $value;
                        unset($scriptProperties[$item][$key]);
                    }
                }
        }
        if(!empty($scriptProperties['select']))
            foreach ($scriptProperties['select'] as $key => $value) {
                if (is_numeric($key) && $detail === $value) {
                    $scriptProperties['select'][$key] = $detailTable . '.value as ' . $detail;
                }
            }
        if(!empty($scriptProperties['groupby']))
            foreach ($scriptProperties['groupby'] as $key => $value) {
                if (is_numeric($key) && $detail === $value) {
                    $scriptProperties['groupby'][$key] = $detailTable . '.value';
                }
            }

    }
    else{
        if($modx->hasPermission(['edit_chunk','edit_template'])){
            return $detail.' не найден в базе данных';
        }
    }
}
if(!empty($scriptProperties['groupby']) && is_array($scriptProperties['groupby'])){
    $scriptProperties['groupby'] = implode(',',$scriptProperties['groupby']);
}
if(!empty($scriptProperties['sortby']['size'])){
    $sortDir = $scriptProperties['sortby']['size'];
    $sizes = ['XS/S','M/L','L/XL','XXS','XS','S','M','L','XL','XXL'];
    unset($scriptProperties['sortby']['size']);
    $scriptProperties['sortby']['FIELD(DetailSize.value,"'.implode('","',$sizes).'")'] = $sortDir;
}
$productsIds = array_map(function($product){return $product["id"];},$products);
$pdoFetch->setConfig(array_merge([
    'class'=>'Modification',
    'innerJoin'=>[
        'msProduct'=>[
            'class'=>'msProduct',
            'on'=>'Modification.product_id = msProduct.id',
        ],
    ],
    "leftJoin"=>$leftJoin,
    'select'=>$select,
    'where'=> array_merge($where,["msProduct.id:IN"=>$productsIds]),
    "limit"=>0,
    'return'=>'data',
    'polyLang'=>false,
],$scriptProperties));
$offers = $pdoFetch->run();
$polylang = $modx->getService('polylang', 'Polylang');
$polylangTools = $polylang->getTools();
foreach($offers as &$offer){
    $options = array(
        'class' => "msProduct",
        'content_id'=>(int) $offer['product_id'],
    );
    $polylangTools->prepareResourceData(function ($key, $value, $context) use (&$offer,&$modx) {
        if($key === "size")
            return;
        $offer['polylang_original_' . $key] = $offer[$key];
        $offer[$key] = $value;
    }, $options);
    if($offer['color']){
        $default = [
            'class'=>'msProductFile',
            'where'=>[
                'product_id' => $offer['product_id'],
                'description' => $offer['color'],
                'parent' => 0,
                'active' => true,
            ],
            "select"=>"url",
            'sortby'=>['rank'=>'ASC'],
            'limit'=>0,
            'return'=>'data'
        ];
        $pdoFetch->setConfig($default,false);
        $images = $pdoFetch->run();
        if(!empty($images)){
            $images = array_map(function($image){return $image["url"];},$images);
            $offer['images'] = $images;
            $offer['image'] = $images[0];
        }
        
    }
}
foreach($products as &$product){
    foreach($offers as &$offer){
        if($product["id"] != $offer["product_id"]){
            continue;
        }
        $product["offers"][] = $offer;
    }
}
if(!empty($tpl)){
    $output = "";
    foreach($products as &$product){
        $output .= $pdoFetch->getChunk($tpl, $product);
    }
    return $output;
}else{
    return $products;
}