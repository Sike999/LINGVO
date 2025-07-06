<?php
class HTMLPage
{
    const db = 'CSV_DB 12';
    public $login;
    public $title;
    public $pdo;
    function __construct($title){
        $this->login = 'user';
        $this->title = $title;
        try{
            $this->pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=" . self::db . ";", "$this->login", '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        }
        catch(PDOException $e){
            echo 'Ошибка:' . $e->getMessage();
        }
    }

    function footer() {
        return "<footer style='color:#edeef0; height:30px; background-color:#202226; border-radius:20px;'> 
                <p style='width:200px; margin:0 auto;'>Copyright © 2025 </p>
                </footer>
                <style>body{margin:10px;}</style>";
    }

    function header(){
        return "<header style='background-color:#202226; border-radius:20px; display:flex; flex-wrap:row-reverse; justify-content:flex-start;'>" . "<a  style = 'color:white; margin-left:20px; text-decoration:none ;' href='http://localhost/new%20era/lab%202/index.php'><h2>На главную</a></h2>"
                . "<h1 style ='margin:20px auto; color:#edeef0;'>" . $this->title . "</h1>";
    }

    function logo(){
        return "<img src='Logo.png' width='80px' style='padding:10px; margin:0;'></header>";
    }

    function menu(){
        
        $res = $this->pdo->query('SELECT `COL 1` FROM `______3`');
        $list = $res->fetchAll(PDO::FETCH_NUM);
        $menu = '';
        $urls = '';
        if(!empty($list)){
        $menu .= '<div style="display:flex;">' . '<div style="width:20%; background-color:#b3b6bd; border-radius:20px; margin:10px; padding:10px;">' . '<ul>';
            $destination="http://localhost/new%20era/lab%202/html.php";
            foreach($list as $item){
                $add = urlencode($item[0]);
                $urls .= '<a href="' . $destination . '?item=' . $add . '">' . '<li type="square">' . $item[0] . '</li>' . '</a>';}
            $menu .= $urls . '</ul>' . '</div>';
        }
        return $menu;
    }

    function content(){
        $content = '';
        if(isset($_GET['item'])){
            $item = $_GET['item'];
            $stmt = $this->pdo->prepare("SELECT * FROM `______3` WHERE `COL 1` = ?");
            $stmt->execute([$item]);
            $check = $stmt->fetchAll();
            if(count($check) > 0){
                $stmt = $this->pdo->prepare('SELECT `COL 5`,`COL 6` FROM `______3` WHERE `COL 1` = ?');
                $stmt->execute([$_GET['item']]);
                $res = $stmt->fetchAll(PDO::FETCH_NUM);
                
                $content .= "<h3 style='text-align:center;'>" . $res[0][0] . "</h3><br><img style='display:block; margin:0 auto;' src='img/" . substr($res[0][1],0,-1) . "'>";
            } else {
                $content .= "<h3 style='text-align:center;'> Неверные данные! </h3>";
            }
        } else {
            $content .= "<h3 style='text-align:center;'>Добро пожаловать на страницу с палатками!</h3>";
        }
        return '<div style="width:80%; background-color:#dfe1e5; border-radius:20px; margin:10px; padding:10px;">' . $content . '</div>' . '</div>';
    }

    function write(){
        return $this->header() .
         $this->logo() .
         $this->menu() .
         $this->content() .
         $this->footer();
    }
}
?>

