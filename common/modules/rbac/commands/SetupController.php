<?php

namespace common\modules\rbac\commands;

use yii\rbac\DbManager;
use yii\helpers\Console;

/**
 * A CLI allowing RBAC Initialisation functionality
 * 
 * Supported commands:
 * 
 * 
 * @name Yii2 RBAC Setup CLI
 * @version 0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-rbac
 * 
 * 
 * 
 */
class AdminController extends \yii\console\Controller {

    /**
     *
     * @var DbManager 
     */
    private $_authManager;

    public function __construct($id, $module, $config = array())
    {
        parent::__construct($id, $module, $config);
        //Setup the authManager as per config 
        $this->_authManager = \Yii::$app->authManager;
    }

    public function actionIndex()
    {
        system('stty echo');
        echo "Welcome to the Humanized RBAC Administrator CLI \n";
        echo "This tool requires Yii 2.0.7 or later \n";
        return 0;
    }

    private function outputItem($action, $name, $type)
    {
        $this->stdout("$action ");
        $this->stdout($name, Console::FG_YELLOW);
        $this->stdout(" $type: ");
    }

    public function actionRemoveAll()
    {
        if ($this->confirm('Are you sure want to remove all authorization data, including roles, permissions, rules, and assignments?')) {
            $this->_authManager->removeAll();
        }
        return 0;
    }

    public function actionRemoveAllAssignments()
    {

        if ($this->confirm('Are you sure want to remove all role assignments?')) {
            $this->_authManager->removeAllAssignments();
        }
        return 0;
    }

    public function actionRemoveAllPermissions()
    {

        if ($this->confirm('Are you sure want to remove all permissions? All parent child relations will be adjusted accordingly.')) {
            $this->_authManager->removeAllPermissions();
        }
        return 0;
    }

    public function actionRemoveAllRoles()
    {

        if ($this->confirm('Are you sure want to remove all roles? All parent child relations will be adjusted accordingly.')) {
            $this->_authManager->removeAllPermissions();
        }
        return 0;
    }

    public function actionRemoveAllRules()
    {

        if ($this->confirm('Are you sure want to remove all rules? All roles and permissions which have rules will be adjusted accordingly')) {
            $this->_authManager->removeAllRules();
        }
        return 0;
    }

    /**
     * 
     * @param type $role
     * @param type $user
     */
    public function actionAssign($roleName, $userName)
    {
        $exitCode = 0;
        $this->outputItem("Searching for", $roleName, "Role in database");
        $role = $this->_authManager->getRole($roleName);
        if (isset($role)) {
            $this->stdout('OK', Console::FG_GREEN);
        } else {
            $this->stdout('FAILED', Console::FG_RED, Console::BOLD);
            $exitCode = 1;
        }
        $this->outputItem("\nSearching for", $userName, "User Account in database");
        $identityClass = \Yii::$app->user->identityClass;
        $user = $identityClass::findByUsername($userName);
        if (isset($user)) {
            $this->stdout('OK', Console::FG_GREEN);
        } else {
            $this->stdout('FAILED', Console::FG_RED);
            return 2;
        }

        $this->stdout('Linking: ');
        if ($this->_authManager->assign($role, $user->id)) {
            $this->stdout('OK', Console::FG_GREEN);
        } else {
            $this->stdout('FAILED', Console::FG_RED);
        }

        $this->stdout("\n");
        return 0;
    }

    public function actionCreateRole($name)
    {
        $role = $this->_authManager->createRole($name);
        $this->outputItem("Creating", $name, "Role");
        return $this->addItem($role);
    }

    public function actionCreatePermission($name)
    {
        $role = $this->_authManager->createPermission($name);
        $this->outputItem("Creating", $name, "Permission");
        return $this->addItem($role);
    }

    private function addItem($item)
    {
        $exitCode = 0;
        //Save the model
        try {
            $this->_authManager->add($item);
            $this->stdout("OK", Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stdout("FAILED", Console::FG_RED);
            $this->stderr("\nGenerated Message: ");
            //Todo: Optional full error message display
            $this->stderr(strtok($e->getMessage(), "\n"), Console::BG_BLUE);
            $exitCode = 1;
        }
        $this->stdout("\n");
        return $exitCode;
    }

    public function actionCreateRule($name)
    {
        
    }

    private function _createItem($name, $type)
    {
        
    }

    /*
     * DbManager Public Properties  
     */

    public function actionAssignmentTable()
    {
        return $this->printActionValue();
    }

    public function actionDefaultRoles($separator = "\n")
    {
        return $this->printActionArray($separator);
    }

    public function actionItemChildTable()
    {

        return $this->printActionValue();
    }

    public function actionItemTable()
    {
        return $this->printActionValue();
    }

    public function actionPermissions($separator = "\n")
    {
        return $this->printActionArray($separator, function($v) {
                    return $v->name;
                });
    }

    public function actionRoles($seperator = "\n")
    {
        return $this->printActionArray($seperator, function($v) {
                    return $v->name;
                });
    }

    public function actionRuleTable()
    {
        return $this->printActionValue();
    }

    /*
     * Private Output Functions
     */

    private function printActionValue()
    {
        $value = lcfirst(\yii\helpers\Inflector::id2camel($this->action->id));
        return $this->printValue($this->_authManager->$value);
    }

    private function printValue($value)
    {
        $this->stdout($value . "\n", Console::FG_CYAN);
        return 0;
    }

    private function printActionArray($seperator, $fn = NULL)
    {
        $proc = lcfirst(\yii\helpers\Inflector::id2camel($this->action->id));

        $result = $this->_authManager->$proc;
        if (isset($fn)) {
            $result = array_map($fn, $result);
        }
        return $this->printValue(implode($seperator, $result));
    }

}
