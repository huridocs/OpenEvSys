<?php

/**
 * DataObject For system users of OpenEvSys.
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *

 * @author	Nilushan Silva <nilushan@respere.com>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */
require_once( APPROOT . 'data/DataObject.php' );
require_once( APPROOT . '3rd/GoogleAuthenticator.php');

class User extends ADODB_Active_Record {

    protected $username = '';
    protected $password = '';
    protected $salt;
    protected $old_password;
    protected $old_salt;
    protected $role = '';
    protected $created = '';  // store the date format used by mysql
    protected $last_login = '';
    protected $user_profile = null;
    protected $user_code;
    private $pkey = array('username');
    public $config;

    public function __construct($table = false, $pkeyarr = false, $db = false, $options = array()) {

        $table = 'user';

        parent::__construct($table, $pkey, $db, $options);

        $this->belongsTo('user_profile', 'username', 'username');
        $this->hasMany('user_code', 'username');
    }

    //getters



    public function getUserName() {
        return $this->username;
    }

    public function getPasswordHash() {
        return $this->password;
    }

    public function getSalt() {

        return $this->salt;
    }

    public function getOldPasswordHash() {
        return $this->old_password;
    }

    public function getOldSalt() {
        return $this->old_salt;
    }

    public function getUserType() {
        return $this->role;
    }

    public function getCreatedDate() {
        $mysqldate = $this->created;
        $phpdate = strtotime($mysqldate);
        return $phpdate;
    }

    public function getLastLoginDate() {
        $mysqldate = $this->last_login;
        $phpdate = strtotime($mysqldate);
        return $phpdate;
    }

    public function getUserProfile() {

        //$this->loadUserProfile();		
        $this->user_profile;
        return $this->user_profile;
    }

    public function getUserCodeCode() {
        //$this->loadUserCode();
        $this->user_code;
        echo '<br /> getusercodecode() :' . $this->user_code->code;
        return $this->user_code->code;
    }

    public function getUserCodeAction() {
        //$this->loadUserCode();
        return $this->user_code->action;
    }

    public function getUserCodeExpiry() {
        //$this->loadUserCode();
        $mysqldate = $this->user_code->expiry;
        $phpdate = strtotime($mysqldate);
        return $phpdate;
    }

    //setters



    public function setUserName($username) {
        $this->username = $username;
    }

    public function setPassword($password) {

        $this->password = shn_auth_generateHash($password, $this->getSalt());
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function setOldPasswordHash($oldPasswordHash) {
        $this->old_password = $oldPasswordHash;
    }

    public function setOldSalt($oldSalt) {
        $this->old_salt = $oldSalt;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setCreatedDate($phpdate) {

        $mysqldate = date('Y-m-d H:i:s', $phpdate);
        $this->created = $mysqldate;
    }

    public function setLastLoginDate($phpdate) {
        $mysqldate = date('Y-m-d H:i:s', $phpdate);
        $this->last_login = $mysqldate;
    }

    public function setUserProfile($user_profile) {
        $this->user_profile = $user_profile;
    }

    public function setUserCodeCode($userCode) {
        $this->user_code->code = $userCode;
    }

    public function setUserCodeCodeGenerated() {
        $this->user_code->code = $this->auth_generateSalt();
    }

    public function setUserCodeAction($action) {
        $this->user_code->action = $action;
    }

    public function setUserCodeExpiry($expiry) {
        $mysqldate = date('Y-m-d H:i:s', $expiry);
        $this->user_code->expiry = $mysqldate;
    }

    /////////////////////////////////////////////////////////////////////

    public function loadUserProfile() {
        //if( isset($this->user_profile  ) ){
        $this->user_profile;
        //}
    }

    public function loadUserCode() {
        //if( $this->user_code == null ){
        $this->user_code;
        //}
    }

    /////////////////////////////////////////////////////////////////////

    public function Save() {


        $ok = parent::Save();
        if (!$ok) {
            $err = $this->ErrorMsg();
            echo $err;
            //throw new ADODB_Exception();// Exception($err);  // should remove exception and add error handling routines
        }

        //Save UserProfile 
        if ($this->user_profile != null) {
            $this->user_profile->username = $this->username;
            $ok = $this->user_profile->Save();
            if (!$ok) {
                $err = $this->ErrorMsg();
                echo $err;
                //throw new ADODB_Exception();// Exception($err);  // should remove exception and add error handling routines
            }
        }

        //Save UserCodes 
        if ($this->user_code != null) {
            $this->user_code->username = $this->username;
            $ok = $this->user_code->Save();
            if (!$ok) {
                $err = $this->ErrorMsg();
                echo $err;
                //throw new ADODB_Exception();// Exception($err);  // should remove exception and add error handling routines
            }
        }

        if ($err == null)
            return true;
    }

    function Delete($field, $value) {
        $db = $this->DB();
        if (!$db)
            return false;
        $table = $this->TableInfo();

        $where = "$field='" . $value . "'";
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $where;
        $ok = $db->Execute($sql);

        if (!$ok) {
            $err = $this->ErrorMsg();
            //throw new DbException($err);
            echo $err;
        }else
            return true;
    }

    public function toString() {


        echo 'Username - ' . $this->getUserName() . '<br />';
        echo 'Password - ' . $this->getPasswordHash() . '<br />';
        echo 'Created  - ' . $this->getCreatedDate() . '<br />';
        echo 'Last loggin- ' . $this->getLastLoginDate() . '<br />';
        $userProfile = $this->getUserProfile();
        //$userProfile = new UserProfile();
        echo 'First Name - ' . $userProfile->getFirstName() . '<br />';
        //var_dump($userProfileO);
    }

    ////authentication related functions////////////////////////////////////////////////////////////

    public function isPasswordMatch($plainTextPassword) {
        //var_dump('password hash' , $this->getPasswordHash() );
        //var_dump('generated' ,  generate_password($plainTextPassword , $this->getSalt() ) );
        return ( $this->getPasswordHash() == shn_auth_generateHash($plainTextPassword, $this->getSalt()) );
    }

    private function auth_generateHash($plainText, $salt) {
        return sha1($salt . $plainText);
    }

    private function auth_generateSalt() {
        return substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }

    //validation functions
    //relasionship Data


    public function TSVSaveMGA($code) {
        if ($this->verifyGACode($code)) {

            $cfg = $this->getConfig();
            $cfg['security']['TSV']['method'] = "MGA";

            $this->setConfig($cfg);
            $this->Save();

            return true;
        }

        return false;
    }

    public function TSVSaveYubiKey($keyId) {
        $cfg = $this->getConfig();
        $cfg['security']['TSV']['method'] = "yubikey";
        $cfg['security']['TSV']['keyId'] = $keyId;

        $this->setConfig($cfg);
        $this->Save();

        return true;
    }

    public function getConfig() {
        $config = array();
        
        if (!empty($this->config)) {
            $config = @json_decode($this->config, true);
        }

        return $config;
    }

    public function setConfig($config) {
        $this->config = json_encode($config);
    }

    public function disableTSV() {
        $cfg = $this->getConfig();
        
        unset($cfg['security']);

        $this->setConfig($cfg);
        $this->Save();
        
        return true;
    }

    /* get Google Authenticator secret key */

    public function getGASk() {
        $result = array('secret' => 'xxxx xxxx xxxx xxxx xxxx xxxx xxxx xxxx');
        $cfg = array();
        if (!empty($this->config)) {
            $cfg = @json_decode($this->config, true);
        }
        if (empty($cfg['security'])) {
            $cfg['security'] = array();
        }
        if (empty($cfg['security']['TSV'])) {
            $cfg['security']['TSV'] = array();
        }

        $ga = new GoogleAuthenticator();

        if (empty($cfg['security']['TSV']['secret'])) {
            $cfg['security']['TSV']['secret'] = $ga->createSecret(16);
            $this->config = json_encode($cfg);
            $this->Save();
        }
        $result['secret'] = $cfg['security']['TSV']['secret'];

        $result['url'] = $ga->getQRCodeGoogleUrl($_SERVER['HTTP_HOST'], $result['secret']);


        return $result;
    }

    /* get code for Google Authenticator */

    private function getGACode() {
        $sk = $this->getGASk();
        $sk = $sk['secret'];
        $ga = new GoogleAuthenticator();

        return $ga->getCode($sk);
    }

    /* verify given Google Authenticator code */

    public function verifyGACode($code) {
        $sk = $this->getGASk();
        $sk = $sk['secret'];
        $ga = new GoogleAuthenticator();

        return $ga->verifyCode($sk, $code);
    }

}
