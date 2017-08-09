<?php
//Base类所在的空间名
namespace houdunwang\model;
//PDO是个系统常量 不能再houdunwang\model这个命名空间下使用
use PDO;
//PDOException是个系统常量 不能再houdunwang\model这个命名空间下使用
use PDOException;
//创建一个base类用来执行连接数据库，查看数据库数据，
//修改数据的一些操作并对应的数据返回给对应的方法中
class Base {
    //保存PDO对象的静态属性
    private static $pdo = null;
    //保存表名属性
    private $table;
    //保存where
    private $where;
   // 当调用BASE类时自动执行这个方法，
    public function __construct($table) {
//    自动执行连接数据库方法
        $this->connect();
        $this->table=$table;
    }

    /**
     * 链接数据库
     */
    private function connect() {
        //如果构造方法多次执行，那么此方法也会多次执行，用静态属性可以把对象保存起来不丢失，
        //第一次self::$pdo为null，那么就正常链接数据库
        //第二次self::$pdo已经保存了pdo对象，不为NULL了，这样不用再次链接mysql了。
        if ( is_null( self::$pdo ) ) {
            try {
//                用C函数早database里面调取要连接的地址和数据库的名称
                $dsn = 'mysql:host='.c('database.db_host').';dbname=' . c('database.db_name');
//                用C函数早database里面调取要连接的用户名和密码
                $pdo = new PDO( $dsn, c('database.db_user'), c('database.db_password') );
                $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//                用C函数早database里面调取php的格式
                $pdo->exec( "SET NAMES " . c('database.db_charset') );
                //把PDO对象放入到静态属性中
                self::$pdo = $pdo;
            } catch ( PDOException $e ) {   //捕获PDO异常错误 $e 是异常对象
//                如果捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
                exit( $e->getMessage() );
            }
        }

    }

    public function save($post){
//     查询当前表的信息
        $tableInfo=$this->q("desc {$this->table}");
        $tableFields=[];
//        获取当前表的字段
         foreach($tableInfo as $info){
             $tableFields[] = $info['Field'];
        }
        $filterData=[];
        foreach($post as $f=>$v){
//            如果属于当前表的字段，那么保留，否则就过滤
            if(in_array($f,$tableFields)){
                $filterData[$f]=$v;
            }
        }
//             Array
//		  (
//			[title] => 标题,
//			[click] => 100,
//		)

//        字段
//        获得传过来数据的键名
        $field=array_keys($filterData);

        $field=implode(',',$field);
//        p($filed); exit();
//        值
        $values=array_values($filterData);
        $values='"'.implode('","',$values).'"';

        $sql="INSERT INTO {$this->table} ({$field}) VALUES ({$values})";

        return $this->e($sql);

    }
    /**
     * where条件
     * @param $where
     *
     * @return $this
     */
    public function where($where){
        $this->where=$where;
        return $this;
    }
    /**
     * 摧毁数据
     */
    public function destory(){
        if(!$this->where){
            exit('delete必须有where条件');
        }
        $sql = "DELETE FROM {$this->table} WHERE {$this->where}";
        return $this->e($sql);
    }

     public function getPrikey(){
         $sql="DESC {$this->table}";
         $data=$this->q($sql);
         //主键
        $primayKey='';
         foreach($data as $v){
             if($v['Key'] =='PRI'){
                 $primayKey = $v['Field'];
                 break;
             }
         }
         return $primayKey;
     }
    public function find($aid){
        $priKey=$this->getPrikey();
        $sql="SELECT * FROM {$this->table} WHERE {$priKey}={$aid}";
        $data=$this->q($sql);
        return current($data);
    }

    /**
     * 修改
     */
    public function update($data){
        if(!$this->where){
            exit('update必须有where条件');
        }

        $set='';
        foreach($data as $field=>$value){
            $set.="{$field}='{$value}',";
        }
         $set=rtrim($set,',');
        $sql="UPDATE {$this->table} SET {$set} WHERE {$this->where}";
        return $this->e($sql);
    }
    /*
         * 获取全部数据
         */
//    创建一个get方法用来获取对应表的所有数据
    public function get(){
//        获取传过来的对应的表的数据，将对应的表添加到获取所有数据的SQL语句中，并通过有结果集的操作完成获取数据，并将获取的数据转换才关联数组返回到对应的对象
        $sql= "SELECT * FROM {$this->table}";
//        通过有结果集的操作执行sql语句完成获取对应表的数据
        $result = self::$pdo->query( $sql );
//        将多去的数据转换成关联数组
        $data= $result->fetchAll(PDO::FETCH_ASSOC);

//        将转好的数据返回给当前的对象
        return $data;
    }

    /**
     * 执行有结果集的操作
     *
     * @param $sql
     */
    public function q( $sql ) {
        try {
//            执行从Model 里传来的sql语序
            $result = self::$pdo->query( $sql );
//            获取表中的所有和这个sql相关的语句 再把它返回到 Model
//            再从Model里返回到Entry  再返回到boot里面 让echo输出
            return $result->fetchAll( PDO::FETCH_ASSOC );
            //捕获PDO异常错误 $e 是异常对象
        } catch ( PDOException $e ) {
//            如果捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
            exit( "SQL错误：" . $e->getMessage() );
        }
    }

    /**
     * 执行没有结果集的操作
     *
     * @param $sql
     */
    public function e( $sql ) {
        try {
//            执行从Model 里传来的sql语序
            $afRows = self::$pdo->exec( $sql );
//            获取表中的所有和这个sql相关的语句 再把它返回到 Model
//            再从Model里返回到Entry  再返回到boot里面 让echo输出
            return $afRows;
            //捕获PDO异常错误 $e 是异常对象
        } catch ( PDOException $e ) {
//            如果捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
            exit( "SQL错误：" . $e->getMessage() );
        }
    }
}