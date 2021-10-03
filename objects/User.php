<?php
    class User{    
        private $UserName;
        private $Name;
        private $EmailAddr;
        private $Department;
        private $Designation;
        private $Roles;
    
        public function __construct($UserName, $Name, $EmailAddr, $Department, $Designation, $Roles){
            $this->UserName = $UserName;
            $this->Name = $Name;
            $this->EmailAddr = $EmailAddr;
            $this->Department = $Department;
            $this->Designation = $Designation;
            $this->Roles = $Roles;
        }
    }

    Class Engineer extends User{
        private $Permission = array();
        
        public function __construct($UserName, $Name, $EmailAddr, $Department, $Designation, $Roles, $Permission){
            $this->UserName = $UserName;
            $this->Name = $Name;
            $this->EmailAddr = $EmailAddr;
            $this->Department = $Department;
            $this->Designation = $Designation;
            $this->Roles = $Roles;
        }

        public function addPermissions($CourseID, $UserType){
            $this->Permissions[] = new Permissions($CourseID, $UserType);
        }

        public function getPermissions(){
            return $this->Permission;
        }
    }
    
    Class Permissions{
        private $CourseID;
        private $UserType;

        public function __construct($CourseID, $UserType){
            $this->CourseID = $CourseID;
            $this->UserType = $UserType;
        }
    }

    
?>
