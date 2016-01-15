<?php

namespace common\modules\user\commands;

use common\modules\user\models\User;
use common\modules\user\models\PasswordReset;
use yii\helpers\Console;

class AdminController extends \yii\console\Controller {

    private $_model;

    public function actionIndex()
    {
        system('stty echo');
        echo "Welcome to the Humanized User Administrator CLI \n";
        echo "This tool requires Yii 2.0.7 or later \n";
        return 0;
    }

    public function actionDel($email)
    {
        $exitCode = 0;
        $this->stdout("Deleting $email: ");
        $deleteCounter = User::deleteAll(['email' => $email]);
        if (1 === $deleteCounter) {
            $this->stdout("OK", Console::FG_GREEN);
        } else {
            $this->stdout("FAILED", Console::FG_RED);
            //Error Handling

            if ($deleteCounter === 0) {
                $exitCode = 1;
                $this->stderr("\nAccount not found", Console::BG_BLUE);
            } else {
                $this->stderr("\nMultiple Accounts Deleted - DB may be in Inconsistent State", Console::BG_BLUE);
                $exitCode = 2;
            }
        }
        $this->stdout("\n");
        return $exitCode;
    }

    public function actionSendPasswordResetLink($email)
    {
        $this->stdout("\nSending Password Reset Link to User: ");
        $this->sendMail($email);
    }

    public function actionSendAccessTokenLink($email)
    {
        
    }

    private function sendMail($email)
    {
        $model = new PasswordReset(['email' => $email]);


        if ($model->validate() && $model->sendEmail()) {
            $this->stdout("OK", Console::FG_GREEN);
        } else {
            $this->stdout("FAILED", Console::FG_RED);
            $this->stderr('\nSystem Unable to reset password for the email provided');
        }
    }

    /**
     * Add a user account to the system.
     * 
     * Upon submitting a valid username/email combination, a prompt is launched to get the password corresponding to the user-account. If no password is provided, the system will email the created. 
     * 
     * @todo Validate e-mail format
     * @todo Implement stty echo alternative for windows (for now windows is not supported)
     * @param type $email Unique E-mail address to be assigned to the user account (mandatory)
     * @param type $user  Unique username to be assigned to the user account (optional) - If no username is provided, the email address is used.
     * @return int 0 for success, 1 for save error
     */
    public function actionAdd($email, $user = NULL)
    {
        $exitCode = 0;
        //Creates User model and sets provided variables
        $this->setupModel($email, $user);
        //Prompt for account password
        $sendPasswordResetMail = $this->promptPassword();
        //Save the model
        try {
            $this->_model->save();
            if ($sendPasswordResetMail) {
                $this->actionSendPasswordResetLink($this->_model->email);
            }
        } catch (\Exception $e) {
            $this->stderr("\nError: ", Console::FG_RED);
            $this->stderr("User Account Creation Failed with Message: ");
            //Todo: Optional full error message display
            $this->stderr(strtok($e->getMessage(), "\n"), Console::BG_BLUE);
            $exitCode = 1;
        }
        //Should remove in stable versions, but nice little fallback just in case
        $this->unhideUserInput();
        //Two newlines B4 program exit
        $this->stderr("\n\n");
        return $exitCode;
    }

    /**
     * Initialises User model, by setting email and username combination
     * along with generating an random authentication key
     * 
     * @param string $email
     * @param string $user
     * @return User
     */
    private function setupModel($email, $user)
    {
        $this->_model = new User();
        if (isset($user)) {
            $this->_model->username = $user;
        } else {
            $this->_model->username = $email;
        }
        $this->_model->email = $email;
        $this->_model->auth_key = \Yii::$app->security->generateRandomString();
    }

    private function promptPassword()
    {
        $exitCode = FALSE;
        $this->hideUserInput();
        $passwd = $this->_promptPassword();
        $confirm = "";
        if ($passwd !== "") {
            $confirm = $this->_promptPassword(TRUE);
        }
        //Restart when passwords do not match OR rejected confirmation
        //Should remove in stable versions, but nice little fallback just in case
        $this->unhideUserInput();
        if (($passwd !== $confirm) || ($passwd === "" && !$this->confirm("\nGenerate Password Automatically?"))) {
            return $this->promptPassword();
        }
        if ($passwd === "") {
            $passwd = \Yii::$app->security->generateRandomString();
            $exitCode = TRUE;
        }
        $this->_model->password = $passwd;
        return $exitCode;
    }

    /**
     * Private function that prompts for and, validates CLI provided user account passwords.
     *  
     * @param bool $confirm - displays Confirmation message when true
     * @return string - returns the password when once valid password is provided
     */
    private function _promptPassword($confirm = FALSE)
    {
        $passwd = false;
        while (false === $passwd) {
            $passwd = $this->prompt("\n" . ($confirm ? "Confirm" : "Submit") . " User Account Password:");
        }
        return $passwd;
    }

    /**
     * @todo Move to a CLI Helper Class
     * @todo Make compatible with Windows (stty alternative)
     */
    private function hideUserInput()
    {
        if (!\yii\helpers\Console::isRunningOnWindows()) {
            system('stty -echo');
        }
    }

    /**
     * @todo Move to a CLI Helper Class
     * @todo Make compatible with Windows (stty alternative)
     */
    private function unhideUserInput()
    {
        if (!\yii\helpers\Console::isRunningOnWindows()) {
            system('stty echo');
        }
    }

}
