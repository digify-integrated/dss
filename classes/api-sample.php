# -------------------------------------------------------------
    #
    # Name       : generate_menu_array
    # Purpose    : Generates menu array for menu generation.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function generate_menu_array($module_id, $username){
        if ($this->databaseConnection()) {
            $response = array(
                'ITEMS' => array(),
                'PARENTS' => array()
            );

            $sql = $this->db_connection->prepare('SELECT MENU_ID, MENU, PARENT_MENU, IS_LINK, MENU_LINK FROM technical_menu WHERE MODULE_ID = :module_id ORDER BY ORDER_SEQUENCE');
            $sql->bindValue(':module_id', $module_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $menu_id = $row['MENU_ID'];

                    $menu_access_right = $this->check_role_access_rights($username, $menu_id, 'menu');

                    if($menu_access_right > 0){
                        $response['ITEMS'][$menu_id] = $row;
                        $response['PARENTS'][$row['PARENT_MENU']][] = $menu_id;
                    }
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_menu
    # Purpose    : Generates menu.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function generate_menu($parent, $menu){
        if ($this->databaseConnection()) {
            $menu_item = '';
            if (isset($menu['PARENTS'][$parent])) {
                foreach ($menu['PARENTS'][$parent] as $item_id) {
                    if (!isset($menu['PARENTS'][$item_id])) {
                       # if($menu['PARENTS'][0] != $menu['PARENTS'][$item_id]){
                        /*if(in_array(, $menu['PARENTS'][0])){
                            $menu_item .= '<li class="nav-item dropdown"><a href="'. $menu['ITEMS'][$item_id]['MENU_LINK'] .'" class="nav-link">'. $menu['ITEMS'][$item_id]['MENU'] .'</a></li>';
                        }
                        else{
                            $menu_item .= '<a href="'. $menu['ITEMS'][$item_id]['MENU_LINK'] .'" class="dropdown-item"">'. $menu['ITEMS'][$item_id]['MENU'] .'</a>';
                        }*/

                        $menu_item .= '<a href="'. $menu['ITEMS'][$item_id]['MENU_LINK'] .'" class="dropdown-item"">'. $menu['ITEMS'][$item_id]['MENU'] .'</a>';
                    }
                    
                    if (isset($menu['PARENTS'][$item_id])) {
                        $menu_item .= '<li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-user-access" role="button">
                                                <span key="t-user-access">'. $menu['ITEMS'][$item_id]['MENU'] .'</span> <div class="arrow-down"></div>
                                            </a>';
                        $menu_item .= '<div class="dropdown-menu" aria-labelledby="topnav-user-access">';
                        $menu_item .= $this->generate_menu($item_id, $menu);
                        $menu_item .= '</div></li>';
                    }
                }
            }

            return $menu_item;
        }
    }
    # -------------------------------------------------------------