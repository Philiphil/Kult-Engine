<?php


class stock extends kult_engine\daoableObject{
	public $_sym = "string";
	public $_prix_achat = 0.0;
	public $_prix_vente = 0.0;
	public $_qtt = 0.0;
	public $_min_qtt = 0.0;
	public $_max_qtt = 0.0;
	public $_min_price = 0.0;
	public $_max_price  = 0.0;
	public $_round  = 0.0;

	public static function round_qtn($qtn,$round){
    	return $round === 1.0 ?  floor($qtn) : number_format($qtn,strpos(substr($round,2), "1")+1,'.','');
	}

	public static function compare($old,$new){
		return $old != 0 ? 100*($new -$old)/$old : 0;
	}
}

class stock_record extends kult_engine\daoableObject{
	public $_sym = "string";
	public $_prix = 0.0;

	public static function clean($maxcrypto){
		$sql = "
		SELECT `_id`
		FROM stock_record
		ORDER BY `_id`
		DESC 
		limit 1";
		$e = kult_engine\connector::query($sql);
		$e->execute();
		$e = $e->fetchAll(\PDO::FETCH_ASSOC);
		$max = count(kult_engine\soros_bot::$cryptos)*$maxcrypto;
		$id = $e[0]["_id"]-$max;
		$sql = "
		DELETE FROM stock_record
		WHERE _id < ". $id ;
		$e = kult_engine\connector::query($sql);
		$e->execute();
	}

	public static function clean_sym($sym){
		$sql = "
		DELETE FROM stock_record
		WHERE _sym like '$sym' ";
		$e = kult_engine\connector::query($sql);
		$e->execute();
	}

	public static function compare($arr){
		$result = 0;
		for($i=0;$i<count($arr)-1;$i++){
		    if($i+1<count($arr)){
		        $result = $result+stock::compare($arr[$i]->_prix, $arr[$i+1]->_prix);
		    }
		}
		return $result/count($arr);
	}
}

class soros_stats extends kult_engine\daoableObject{
	public $_current_btc_val=0.0;
	public $_timestamp="";
}