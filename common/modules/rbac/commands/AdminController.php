<?php

namespace common\modules\rbac\commands;

use yii\rbac\DbManager;
use yii\helpers\Console;

/**
 * A CLI allowing basic Yii2 DBManager driven RBAC functions.
 * It aims to be a direct port of the relevant parts yii\rbac\DbManager related to RBAC management
 * 
 * Supported commands:
 * 
 * CRUD Operations
 * 
 * > rbac/admin/assign <role-name:required> <user-name:required>
 * 
 * > rbac/admin/remove-all
 * 
 * > rbac/admin/remove-all-assignments
 * 
 * >rbac/admin/remove-all-permissions
 * 
 * >rbac/admin/remove-all-roles
 * 
 * > rbac/admin/remove-all-rules
 * 
 * @name Yii2 RBAC CLI
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

    /*
     * Auth Item Creation Commands
     * Methods create named items and add them directly to the database
     */

    /**
     * 
     * @param string $name
     * @return int The exit-code, 0 for success, 601 (DB) or 602 (Exception) for link errors
     */
    public function actionCreateRole($name)
    {
        $role = $this->_authManager->createRole($name);
        return $this->link("add", ['role' => $role], ['roleName' => $name]);
    }

    /**
     * 
     * @param string $name
     * @return int The exit-code, 0 for success, 601 (DB) or 602 (Exception) for link errors
     */
    public function actionCreatePermission($name)
    {
        $permission = $this->_authManager->createPermission($name);

        return $this->link("addItem", ['permission' => $permission], ['permissionName' => $name]);
    }

    /**
     * 
     * @param string $name
     * @return int The exit-code, 0 for success, 601 (DB) or 602 (Exception) for link errors
     */
    public function actionCreateRule($name)
    {
        $this->stderr('Due Version 0.2');
        return 1;
    }

    /*
     * Auth Item Update Commands
     * Methods update the names of the item
     */

    /**
     * 
     * @param type $role
     * @param type $user
     */
    public function actionAssign($roleName, $userName)
    {
        $exitCode = 0;
        $role = $this->getRole($roleName);
        $user = $this->getUser($userName);

        (isset($role) ? $exitCode = 0 : $exitCode = 1);
        (isset($user) ? $exitCode = 0 : $exitCode = 2);

        if ($exitCode === 0) {
            $this->stdout("\n");
            $exitCode = $this->link('assign', ['role' => $role, 'user' => $user->id], ['roleName' => $roleName, 'userName' => $userName]);
        }

        $this->stdout("\n");
        return $exitCode;
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
     * Bulk Removal Actions
     */

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

    /*
     * Private Class Helper Functions
     */

    private function getRole($roleName)
    {
        $this->outputItem("Searching for", $roleName, "Role in database");
        $role = $this->_authManager->getRole($roleName);
        if (isset($role)) {
            $this->stdout('OK', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stdout('FAILED', Console::FG_RED, Console::BOLD);
        }
        return $role;
    }

    private function getPermission($permissionName)
    {
        $this->outputItem("Searching for", $permissionName, "Role in database");
        $permission = $this->_authManager->getRole($permissionName);
        if (isset($permission)) {
            $this->stdout('OK', Console::FG_GREEN);
        } else {
            $this->stdout('FAILED', Console::FG_RED, Console::BOLD);
        }
        return $permission;
    }

    private function getUser($userName)
    {
        $this->outputItem("\nSearching for", $userName, "User Account in database");
        $identityClass = \Yii::$app->user->identityClass;
        $user = $identityClass::findByUsername($userName);
        if (isset($user)) {
            $this->stdout('OK', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stdout('FAILED', Console::FG_RED, Console::BOLD);
        }
        return $user;
    }

    private function printActionValue()
    {
        $value = lcfirst(\yii\helpers\Inflector::id2camel($this->action->id));
        return $this->printValue($this->_authManager->$value);
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

    private function printValue($value)
    {
        $this->stdout($value . "\n", Console::FG_CYAN, Console::BOLD);
        return 0;
    }

    private function printLink($params, $source)
    {
        $keys = array_keys($params);
        $this->stdout('Linking ');
        $this->stdout(array_shift($source), Console::FG_YELLOW, Console::BOLD);
        $key = array_shift($keys);
        $this->stdout("($key)");
        if (count($keys)) {
            $this->stdout('to ');
            $this->stdout(array_shift($source), Console::FG_YELLOW, Console::BOLD);
            $key = array_shift($keys);
            $this->stdout("($key)");
        }
        $this->stdout(": ");
    }

    private function link($fn, $params, $source)
    {
        $exitCode = 0;
        $this->printLink($params, $source);
        //Save the model
        try {
            if (!$this->_link($fn, $params)) {
                $exitCode = 602;
            }
        } catch (\Exception $e) {
            $this->errorMsg(strtok($e->getMessage(), "\n"));
            //Todo: Optional full error message display
            $exitCode = 601;
        }
        $this->stdout("\n");
        return $exitCode;
    }

    private function _link($fn, $params)
    {
        $result = true;
        if (count($params) > 1) {
            $result = $this->_authManager->$fn(array_shift($params), array_shift($params));
        } else {
            $result = $this->_authManager->$fn(array_shift($params));
        }
        if ($result) {
            $this->stdout("OK", Console::FG_GREEN, Console::BOLD);
        } else {
            $this->errorMsg('Linking function returned FALSE');
        }
        return $result;
    }

    private function outputItem($action, $name, $type)
    {
        $this->stdout("$action ");
        $this->stdout($name, Console::FG_YELLOW, Console::BOLD);
        $this->stdout(" $type: ");
    }

    private function errorMsg($msg)
    {
        $this->stdout("FAILED", Console::FG_RED, Console::BOLD);
        $this->stderr("\nGenerated Message: ");
        $this->stderr($msg, Console::BG_BLUE);
    }

}
