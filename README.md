# jxNewsletter - Display, Filter & Export Users for Newletter

*Module for backend / admin of OXID eShops for displaying, filtering and exporting users.*

## Installation and Setup
1. Copy all files under **copy_this** to the root folder of your shop.
2. In the shop admin goto settings/modules and activate **jxNewsletter**
3. The module has now its own menu item under **User administration**


## Screenshots
![screenshot](https://github.com/job963/jxNewsletter/raw/master/docs/img/userlist.png)

### Column Selection
![screenshot](https://github.com/job963/jxNewsletter/raw/master/docs/img/enabledisablecolumns.png)

### Add userdefined columns
![screenshot](https://github.com/job963/jxNewsletter/raw/master/docs/img/userdefinedcolumns.png)


## Customization
New fields can be defined by creating a new php file starting with ```jxnewsletter_``` and ending with ```.inc.php``` like eg. ```jxnewsletter_mynewfield.inc.php```.  
This file must contain a array definition for an array called ```$aIncFields```.

#### Example
```php
$aIncFields = array("name"  => "oxbirthdate",  
                    "field" => "u.oxbirthdate AS   oxbirthdate" 
                       );
```


## Release Notes
* **0.3**
  * Enable/disable columns by settings (cust-no, company, address, phone, country, language, ...)
  * Calculated values (revenue, number of orders, number of returns, ...)
  * User definable columns

*  **0.2**
  * Configurable export (separator char, enable/disable column headers