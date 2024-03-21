<div class="meniu">
    <ul>
        <?php
        if($appCode != "")
        {
            $query_menu = "SELECT seed_apps.appCode, seed_apps.appName, seed_apps.appDescription, seed_apps.icon
                        FROM seed_apps WHERE seed_apps.appCode = '".$appCode."';";
        }
        else
        {
            ?>
            <li><a href='./' title="Home">&#127968; HOME</a></li>
            <?php
            $query_menu = "SELECT seed_apps.appCode, seed_apps.appName, seed_apps.appDescription, seed_apps.icon
                        FROM seed_apps
                        WHERE active = 1
                        AND favorite = 1
                        ORDER BY seed_apps.appName;";
        }
        $result_menu = $conn -> query($query_menu);
        while($row_menu = $result_menu -> fetch_object())
        {
            $appCode_m = $row_menu->appCode;
            $appName_m = $row_menu->appName;
            $appDescription_m = $row_menu->appDescription;
            $icon_m = $row_menu->icon;
            ?>
            <li <?php if($row_menu->appCode == $appCode) {print("class='selectat current_app'");} ?>>&nbsp;&nbsp;&nbsp;<?php print ($icon_m); ?> <a href='indexApp.php?app=<?php print ($appCode_m); ?>' title="<?php print($appDescription_m); ?>"><?php print ($appName_m); ?></a></li>
        <?php
        }
        ?>

        

        <br/><br/><b>Shortcuts</b><br/><br/>
        <?php
        if($appCode != "")
        {
        $query_menu = "SELECT seed_apps.appCode, seed_apps.appName, seed_apps.appDescription, 
                            CASE WHEN seed_menus.icon IS NOT NULL THEN seed_menus.icon
                            ELSE seed_apps.icon
                            END AS icon,
                            seed_menus.tableName, seed_menus.menuText
                        FROM seed_apps
                        INNER JOIN seed_menus ON seed_apps.appCode = seed_menus.appCode
                        WHERE seed_apps.appCode = '".$appCode."';";
        }
        else
        {
        $query_menu = "SELECT seed_apps.appCode, seed_apps.appName, seed_apps.appDescription, 
                            CASE WHEN seed_menus.icon IS NOT NULL THEN seed_menus.icon
                            ELSE seed_apps.icon
                            END AS icon,
                            seed_menus.tableName, seed_menus.menuText
                        FROM seed_apps
                        INNER JOIN seed_menus ON seed_apps.appCode = seed_menus.appCode
                        AND seed_menus.favorite = 1
                        ORDER BY seed_apps.appCode, seed_apps.appName;";
        }
        //print($query_menu);
        $result_menu = $conn -> query($query_menu);
        while($row_menu = $result_menu -> fetch_object())
        {
            $appCode1 = $row_menu->appCode;
            $appName = $row_menu->appName;
            $menuItem = $row_menu->tableName;
            $menuText = $row_menu->menuText;
            $icon = $row_menu->icon;
            ?>
            <li <?php if($row_menu->tableName == $table) {print("class='selectat'");} ?>><a href='./entityView.php?app=<?php print($appCode1); ?>&table=<?php print($menuItem); ?>' title="<?php print($appName . " - " . $menuItem); ?>"><?php print($icon); ?> <?php print(" " . $menuText); ?></a></li>
        <?php
        }
        ?>

        
        <?php
        if($appCode != "")
        {
        ?>
            <br/><b>Filters</b><br/><br/>
            <?php
            if($appCode != "")
            {
                $query_menu = "select * from seed_nomenclatures WHERE appCode = '".$appCode."';";
            }
            else
            {
                $query_menu = "select * from seed_nomenclatures;";
            }
            //print($query_menu);
            $result_menu = $conn -> query($query_menu);
            while($row_menu = $result_menu -> fetch_object())
            {
                $menuItem = $row_menu->tableName;
                $filterText = $row_menu->filterText;
                ?>
            <li <?php if($row_menu->tableName == $table) {print("class='selectat'");} ?>><a href='./entityView.php?app=<?php print($appCode1); ?>&table=<?php print($menuItem); ?>' title="Filter by <?php print($menuItem); ?>">&#9889; <?php print($filterText); ?></a></li>
            <?php
            }
        }
        ?>


        <br/><hr/><br/>
        <li><a href='./report.php?app=_system&id=tableUsage'>&#9888; DevOps: tables tools</a></li>
        
    </ul>
    
    
</div>