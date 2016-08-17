# PHP Coding Styleguide

We follow following coding style guide, and PSR-2 coding style guide (https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

Check <a href="https://github.com/radicalloop/coding-styleguide/blob/master/php/sample.php">sample.php</a> here.

- **PHP Tags**: Short tags are never allowed. PHP code must always be delimited by the full-form, standard PHP tags:

    ```php
    <?php
    
    ?>
    ```
- **Indentation**:We use spaces for indentation and **no tabs!** 
    ```php 
    <?php
    /**
     * Returns the name of the currently set context.
     *
     * @return string Name of the current context
     */
    public function getContextName()
    {
        return $this->contextName;
    }
    ```

- **String**: Always write string in single quotes unless string has a variable 
   
    ```php
    $str = 'This is string';
    
    $str = "This string with variable {$var}";
    ```
    **String Concatenation**
    
    ```php
    $company = 'RadicalLoop' . ' ' . 'Technolabs';
    ```
- **Array**: 
    
    ```php
    $sampleArray = array(
        'firstKey'  => 'firstValue',
        'secondKey' => 'secondValue',
    );
    
    // OR
    
    $sampleArray = [
        'firstKey'  => 'firstValue',
        'secondKey' => 'secondValue',
    ];
    ```
- **if conditions**:
   
    ```php
    if ($something)
    {
        return '...';
    }
    
    if (($a === $b)
        && ($b === $c)
        || (Foo::CONST === $d)) 
    {
        $a = $d;
    }
    elseif ($a === $b)
    {
        $a = $b;
    }
    
    if (!$something)
    {
    
    }
    ```
- **foreach, for, while**:
    ```php
    <?php
    
    foreach ($teams as $team)
    {
    
    }
    
    for ($t=0; $t < $teamCnt; $t++)
    {
    
    }
    
    while (true)
    {
        ...
    }
    
    do 
    {
        ...
    } while ($expr)
    ```
- **Database column name**: db columns name are **snake_case** (with "_" underscore) but property name in php class is **camelCase**.
    
    ```mysql
    // DB column name ex. user_id and class property name will be userId 
    ```
- **Class**: 
    - Class name must be declared like **TeamService** (First character capital)
    - Class constant must be in upper case with underscore. ex. CLASS_CONSTANT
    - Class method name must be in **camelCase** ex. ``` public function getUsers() {} ```
    - Class property names and arguments must be in **camelCase**.
    - Variable name must be in **camelCase**.
    

    ```php 
    <?php
    namespace App\Service;
    
    class UserService extends AbstractService
    {
        const USER_ROLE = 'guest';
        
        public function getUserById($userId, $roleId = 1)
        {
        
        }
    }
    ```

- **Namespace and use Declarations**
    There must be one blank line after the namespace declaration.
    There must be one blank line after the use block.

    ```php
    <?php
    namespace Vendor\Package;
    
    use FooClass;
    use BarClass as Bar;
    use OtherVendor\OtherPackage\BazClass;
    
    ```

- **Sublime Editor Configuration**

    ```javascript
    {
        "default_encoding": "UTF-8",
        "detect_indentation": false,
        "draw_white_space": "all",
        "font_size": 12.0,
        "rulers":
        [
            100
        ],
        "show_encoding": true,
        "show_line_endings": true,
        "tab_size": 4,
        "translate_tabs_to_spaces": true,
        "trim_trailing_white_space_on_save": true,
        "word_wrap": true,
        "wrap_width": 120
    }
    ```

