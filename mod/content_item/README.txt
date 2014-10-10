1. Copy the directoey under mod directory.
2. useradd_content_item.php -> actions/
3. Different useradd_content_item.php -> views\default\account\forms\
4. \engine\lib\user.php  -> add the line 
		register_action("useradd_content_item",true);

after line register_action("useradd",true);


5. admin/user_opt/search_content_item

6. profile/action/edit.php
7. profile/edit.php

