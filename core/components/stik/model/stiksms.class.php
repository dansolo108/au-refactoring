<?php

/**
 * The base class for maxma.
 */

class stikSms
{

  /* @var modX $modx */
  public $modx;
  public $namespace = 'stik';
  public $config = [];

  /**
   * @param modX $modx
   * @param array $config
   */
  function __construct(modX &$modx, array $config = [])
  {
    $this->modx = &$modx;
    $this->namespace = $this->modx->getOption('namespace', $config, 'stik');
    $corePath = $this->modx->getOption('core_path') . 'components/stik/';
    $assetsPath = $this->modx->getOption('assets_path') . 'components/stik/';
    $assetsUrl = $this->modx->getOption('assets_url') . 'components/stik/';
    $this->config = array_merge([
      'corePath' => $corePath,
      'assetsPath' => $assetsPath,
      'modelPath' => $corePath . 'model/',
      'processorsPath' => $corePath . 'processors/',

      'assetsUrl' => $assetsUrl,
      'actionUrl' => $assetsUrl . 'action.php',
      'connectorUrl' => $assetsUrl . 'connector.php',
    ], $config);

    $this->modx->addPackage('stik', $this->config['modelPath']);
  }

  public function preparePhone($phone = '')
  {
    return preg_replace('/[^0-9]/', '', $phone);
  }

  public function findUser($phone)
  {
    $userProfile = $this->modx->getObject('modUserProfile', ['mobilephone' => $phone]);
    if ($userProfile) return $userProfile->getOne('User');
    return false;
  }

  public function register(int $phone)
  {

    /** @var modUser $user */
    $user = $this->modx->newObject('modUser');
    $user->set('username', $phone);
    $user->set('password', $user->generatePassword());
    $user->save();
    $profile = $this->modx->newObject('modUserProfile');
    $profile->set('email', '');
    $profile->set('mobilephone', $phone);
    $profile->set('internalKey', $user->get('id'));
    // сохраняем id пользователя перешедшего по специальной ссылке в AMO
    $amoUserid = $_SESSION['amo_userid'];
    if ($amoUserid) {
      $profile->set('amo_userid', $amoUserid);
      $contact = $this->modx->newObject('amoCRMUser', ['user' => $profile->get('internalKey'), 'user_id' => $amoUserid]);
      $contact->save();
    } else {
      $profile->set('amo_userid', null);
    }

    $getLoyalty = $_POST['join_loyalty'];
    if ($getLoyalty) {
      $profile->set('join_loyalty', 1);
    }

    if (is_object($user)) {
      $group = $this->modx->getObject('modUserGroup', ['name' => 'Users']);
      $user->joinGroup($group->get('id'));
    }

    $user->addOne($profile);
    $profile->save();

    return $user;
  }

  public function authenticate(modUser $user, string $ctx)
  {
    if ($this->modx->getObject('modContext', $ctx) && !$this->modx->user->isAuthenticated($ctx)) {
      $user->addSessionContext($ctx);
      return true;
    } else {
      return false;
    }
  }

  public function phoneFormat(string $phone)
  {
    $phone = trim($phone);
    $formattedPhone = preg_replace(
      array(
        '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
        '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
        '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
        '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
        '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
        '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
      ),
      array(
        '+7$2$3$4$5',
        '+7$2$3$4$5',
        '+7$2$3$4$5',
        '+7$2$3$4$5',
        '+7$2$3$4',
        '+7$2$3$4',
      ),
      $phone
    );
    if (strlen($formattedPhone) < 12) {
      $this->modx->log(modX::LOG_LEVEL_ERROR, print_r('В номере ' . $formattedPhone . ' меньше 11 символов', true));
      return false;
    } elseif (strlen($formattedPhone) > 12) {
      $this->modx->log(modX::LOG_LEVEL_ERROR, print_r('В номере ' . $formattedPhone . ' больше 11 символов', true));
      return false;
    }
    return $formattedPhone;
  }

  public function findUserByPhone(string $phone)
  {
    $formattedPhone = $this->phoneFormat($phone);
    $formattedPhone2 = $this->preparePhone($formattedPhone);

    $userProfile = $this->modx->getObject('modUserProfile', ['mobilephone' => $formattedPhone]);

    if (!$userProfile)
      $userProfile = $this->modx->getObject('modUserProfile', ['mobilephone' => $formattedPhone2]);

    if (!$userProfile)
      $formattedPhone2[0] = '8';
      $userProfile = $this->modx->getObject('modUserProfile', ['mobilephone' => $formattedPhone2]);

    if ($userProfile) return $userProfile->getOne('User');

    $this->modx->log(modX::LOG_LEVEL_ERROR, print_r('Юзер с номером ' . $formattedPhone . ' не существует', true));
    return false;
  }

  public function createUser(string $phone)
  {
    if (!empty($phone)) {

      $formattedPhone = $this->phoneFormat($phone);
      $email = $this->preparePhone($formattedPhone) . '@' . $_SERVER['HTTP_HOST'];
      $password = md5($formattedPhone);

      $user = $this->modx->newObject('modUser', array('username' => $email));
      $user->set('password', $password);
      
      $user->save();
      
      $group = $this->modx->getObject('modUserGroup', ['name' => 'Users']);
      $user->joinGroup($group->get('id'));

      $profile = $this->modx->newObject('modUserProfile');

      $profile->set('fullname', 'Дорогой Клиент <3');
      $profile->set('mobilephone', $formattedPhone);
      $profile->set('email', $email);

      $joinLoyalty = $_POST['join_loyalty'];
      if ($joinLoyalty) {
        $profile->set('join_loyalty', 1);
      }
     
      $user->addOne($profile);
      
      $profile->save();
      $user->save();

      return $user;
    }
  }
}
