<?php
  namespace Views;
 
class Boilerplate
{  
    /**
     * Includes HTML document headers and opening body tag
     */
    public function BeginHTML()
    {  
        require_once "includes/document-head.inc.php";
    }
    /**
     * Includes HTML document closing body tags
     */
    public function EndHTML()
    {  
        require_once "includes/document-end.inc.php";
    }
    /**
     * Adds a sidebar to a div.
     * Requires the parent div with class 'wrapper'
     */
    // public function AddSideBar()
    // {
    //     require_once "Sidebar.php";
    // } 
    /**
     * Adds an action menu navbar
     * Requires parent div to be 'container-fluid' which is 
     * a child of class '.navbar'
     */
    // public function AddNavActionItems()
    // {
    //     require_once "NavActionItems.php";
    // } 
}
