<?php
/** @var $modx **/

function phoneFormatting(string $phone)
{
    $trim = trim($phone);
    $formatted = preg_replace(
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
        $trim
    );
    return $formatted;
}

function findUserByPhone(string $phone)
{
    global $modx;
    $profile = $modx->getObject('modUserProfile', ['mobilephone' => $phone]);
    if ($profile) return $profile->getOne('User');
    return false;
}

function createUser(string $phone)
{
    global $modx;
    $email = $phone . '@' . $_SERVER['HTTP_HOST'];
    $password = md5($phone);

    $user = $modx->newObject('modUser', array('username' => $email));
    $user->set('password', $password);

    $user->save();

    $group = $modx->getObject('modUserGroup', ['name' => 'Users']);
    $user->joinGroup($group->get('id'));

    $profile = $modx->newObject('modUserProfile');

    $profile->set('fullname', 'Дорогой Клиент <3');
    $profile->set('mobilephone', $phone);
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

function auth(modUser $user, string $ctx = 'web')
{
    global $modx;
    if ($modx->getObject('modContext', $ctx) && !$modx->user->isAuthenticated($ctx)) {
        $user->addSessionContext($ctx);
        return true;
    }
    return false;
}

// $modx->log(modX::LOG_LEVEL_ERROR, print_r('$response: ' .print_r($response, true), true));
$modx->log(modX::LOG_LEVEL_ERROR, print_r('$modx->event->name: ' .print_r($modx->event->name, 1), 1));

switch ($modx->event->name) 
{
    // case "SMSAfterCodeCheck":
    case "SMSBeforeCodeSend":

        // $modx->log(modX::LOG_LEVEL_ERROR, print_r('auth works', true));

        // $val = &$modx->event->returnedValues;

        // $modx->log(modX::LOG_LEVEL_ERROR, print_r('$val: ' .print_r($val, true ), true));
        // $modx->log(modX::LOG_LEVEL_ERROR, print_r('response: ' .print_r($response), true));

        // $modx->log(1, print_r(array_keys($scriptProperties), 1));
        // $modx->log(1, print_r($code, 1));
        // $modx->log(1, print_r($mode, 1));
        // $modx->log(1, print_r($values, 1));
        // $modx->log(1, print_r($phone, 1));

        // $val

        // if ($response['success']) {

            $phone = $this->phoneFormatting($phone);
            $user = $this->findUserByPhone($phone);

            if ($user == false) {
                $user = $this->createUser($phone);
            }

            $this->auth($user);
            header("Refresh:0");
        // }

        break;
}