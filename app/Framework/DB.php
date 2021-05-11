<?php
namespace App\Framework;

class DB {
    private static $_instance = null;
    private static $queueWith = [];
    private static $newQueueWith = [];
    public static $collections = [];
    public static $paginationLinks = [];
    public static $calledClass = null;

    public static $koneksi;
    
    public function __construct() {
        $this->koneksi();
    }
	
	public static function koneksi() {
		/* You can edit this on /.env */
		global $dbHost,$dbUser,$dbPass,$dbName;
		$dbHost = env('DB_HOST');
        $dbUser = env('DB_USER');
        $dbPass = env('DB_PASS');
		$dbName = env('DB_NAME');
		
        self::$koneksi = new \mysqli($dbHost, $dbUser, $dbPass, $dbName);
	}
	
	/*
		This is a query builder. For usage, check the documentation
	*/
	public function checkWhereAvailable($query) {
        $p = explode("WHERE", $query);
		return @$p[1] != "" ? true : false;
    }
    public function isContain($word, $query) {
        $p = explode($word, $query);
        return @$p[1] != "" ? true : false;
    }
    public function table($namaTabel) {
        global $query;
        global $tabel,$res;
        $query = "";
        $res = [];
        $tabel = $namaTabel;

        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function select() {
        global $query;
		global $tabel;
		$kolom = func_get_args();
		$printCol = "";
		if (count($kolom) == 1) {
			$printCol = $kolom[0];
		}
		if (count($kolom) == 0) {
			$printCol = "*";
		}

		if (count($kolom) > 1) {
			$i = 0;
			foreach ($kolom as $key => $kol) {
				$separator = $i++ < count($kolom) - 1 ? "," : "";
				$printCol .= "$kol".$separator;
			}
		}

        $query = "SELECT $printCol FROM $tabel";
        return $this;
    }
    public function innerJoin($tabel, $firstFK, $operator, $secondFK) {
        global $query;
        $query .= " INNER JOIN ".$tabel." ON $firstFK ".$operator." $secondFK";
        return $this;
    }
    public function leftJoin($tabel, $firstFK, $operator, $secondFK) {
        global $query;
        $query .= " LEFT JOIN ".$tabel." ON $firstFK ".$operator." $secondFK";
        return $this;
    }
    public function rightJoin($tabel, $firstFK, $operator, $secondFK) {
        global $query;
        $query .= " RIGHT JOIN ".$tabel." ON $firstFK ".$operator." $secondFK";
        return $this;
    }
    public function create($data) {
        global $query;
        global $tabel;

        if (self::$koneksi == null) {
            self::koneksi();
        }
        self::checkIfTableStillNull();

        $query = "INSERT INTO $tabel (";
        $i = 0;
        foreach($data as $key => $value) {
            $separator = ($i++ < count($data) - 1) ? ", " : "";
            $query .= "$key" . $separator;
        }
        $query .= ", created_at";
        $query .= ") VALUES (";

        $a = 0;
        foreach($data as $key => $value) {
            if (preg_match("/'/", $value)) {
                $quote = "'";
                $value = str_replace("'", "''", $value);
            }else if (preg_match('/"/', $value)) {
                $quote = '"';
                $value = str_replace('"', '""', $value);
            }else {
                $quote = "'";
            }
            $separator = ($a++ < count($data) - 1) ? ", " : "";
            $query .= "$quote$value$quote" . $separator;
        }
        $query .= ", '".date('Y-m-d H:i:s')."'";
        $query .= ")";

        $savingData = mysqli_query(self::$koneksi, $query);
        if ($savingData) {
            $lastInsertedID = mysqli_insert_id(self::$koneksi);
            $queryGetLatest = mysqli_query(self::$koneksi, "SELECT * FROM " . $tabel . " WHERE id = " .$lastInsertedID);
            return mysqli_fetch_assoc($queryGetLatest);
        }
        return false;
    }
    public function delete() {
        global $query,$tabel;
        self::checkIfTableStillNull();
        $query = substr($query, 0, 0) . "DELETE FROM $tabel " . substr($query, 1);
        return mysqli_query(self::$koneksi, $query);
    }

    public function orWhere($filter, $operator = NULL, $value = NULL) {
        global $query;
        self::checkIfTableStillNull();

        $query .= " OR ";
        
        if(is_array($filter)) {
            $i = 0;
            $totalFilter = count($filter);
            foreach ($filter as $key => $value) {
				$separator = $i++ < $totalFilter - 1 ? " AND " : "";
                $query .= $value[0] . " " . $value[1] . " '" . $value[2] . "'" . $separator;
            }
        }else {
            $query .= " $filter $operator '$value'";
        }
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public static function where($filter, $operator = NULL, $value = NULL) {
        global $query;
        $adaWhere = self::checkWhereAvailable($query);
        $query .= $adaWhere ? " AND" : " WHERE ";

        self::checkIfTableStillNull();
        
        $operators = ["=",">",">=","<=","<","LIKE","NOT LIKE"];
        
        if(is_array($filter)) {
            $i = 0;
            $totalFilter = count($filter);
            foreach ($filter as $key => $value) {
                $separator = $i++ < $totalFilter - 1 ? " AND " : "";
                if (!in_array($value[1], $operators)) {
                    $query .= $value[0] . " = '" . $value[1] . "'" . $separator;
                }else {
                    $query .= $value[0] . " " . $value[1] . " '" . $value[2] . "'" . $separator;
                }
            }
        }else {
            if (!in_array($operator, $operators)) {
                $query .= " $filter = '$operator'";
            }else {
                $query .= " $filter $operator '$value'";
            }
        }
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function whereBetween($kolom, $value) {
        global $query;
        $adaWhere = self::checkWhereAvailable($query);
        if($adaWhere) {
            $query .= " AND $kolom BETWEEN '$value[0]' AND '$value[1]'";
        }else {
            $query .= " WHERE $kolom BETWEEN '$value[0]' AND '$value[1]'";
        }
        return $this;
    }
    public function limit($posisi, $batas = NULL) {
        /*
         * DEPRACATED METHOD, PLEASE USE paginate() INSTEAD
         */
        global $query;
        if($batas == "") {
            $batas = $posisi;
            $query .= " LIMIT $batas";
        }else {
            $query .= " LIMIT $posisi,$batas";
        }
        return $this;
    }
    public function orderBy($kolom, $mode = NULL) {
        global $query;
        
        if(is_array($kolom)) {
            $query .= " ORDER BY ";
            $i = 0;
            $totalFilter = count($kolom);
            foreach ($kolom as $row) {
                $separator = ($i++ < $totalFilter - 1) ? ", " : "";
                $query .= "$row[0] $row[1]".$separator;
            }
        }else {
            $query .= " ORDER BY $kolom $mode";
        }
        return $this;
	}
	public function query($qry, $realQuery = NULL) {
        $dbHost = env('DB_HOST');
        $dbUser = env('DB_USER');
        $dbPass = env('DB_PASS');
		$dbName = env('DB_NAME');
        $conn = new \mysqli($dbHost, $dbUser, $dbPass, $dbName);
		if ($realQuery != NULL) {
            $run = mysqli_query($qry, $realQuery);
            $res = [];
            while($data = mysqli_fetch_assoc($run)) {
                $res[] = $data;
            }
			return $res;
		}
		return mysqli_query($conn, $qry);
	}

    public function execute() {
        global $query;
        $res = mysqli_query(self::$koneksi, $query);
        return $res;
    }

    public function update($data) {
        global $query;
        global $tabel;

        self::checkIfTableStillNull();

        $i = 0;
        foreach($data as $key => $value) {
            if (preg_match("/'/", $value)) {
                $quote = "'";
                $value = str_replace("'", "''", $value);
            }else if (preg_match('/"/', $value)) {
                $quote = '"';
                $value = str_replace('"', '""', $value);
            }else {
                $quote = "'";
            }
			$separator = $i++ < count($data) - 1 ? "" : ", ";

            $query = substr($query, 0, 1) . "$key = $quote$value$quote $separator " . substr($query, 1);
        }

        $query = substr($query, 0, 0) . "UPDATE $tabel SET " . substr($query, 1);
        $updatingData = mysqli_query(self::$koneksi, $query);
        if ($updatingData) {
            $lastAffectedID = mysqli_affected_rows(self::$koneksi);
            $getUpdatedData = mysqli_query(self::$koneksi, "SELECT * FROM " . $tabel . " WHERE id = " . $lastAffectedID);
            return mysqli_fetch_assoc($getUpdatedData);
        }
        return false;
    }
    public function toSql() {
		global $query;
		return $query;
	}
    public function first() {
        global $query,$tabel;
        $kolom = func_get_args();
		$printCol = "";
		if (count($kolom) == 1) {
			$printCol = $kolom[0];
		}
		if (count($kolom) == 0) {
			$printCol = "*";
		}

		if (count($kolom) > 1) {
			$i = 0;
			foreach ($kolom as $key => $kol) {
				$separator = $i++ < count($kolom) - 1 ? "," : "";
				$printCol .= "$kol".$separator;
			}
        }

        $query = substr($query, 0, 0) . "SELECT $printCol FROM $tabel " . substr($query, 1);

        $runQuery = mysqli_query(self::$koneksi, $query);
		$data = mysqli_fetch_assoc($runQuery);
		if (!$data) return false;
        $query = "";
        
        if (count(self::$newQueueWith) != 0) {
            $data = $this->doRelationJobNew($data);
        }

		return self::toObject($data);
    }
    public function doRelationJobNew($data) {
        global $tabel;
        foreach (self::$newQueueWith as $queue) {
            $foreignClass = $queue['foreignClass'];
            $foreignKey = $queue['foreignKey'];
            $relationshipType = $queue['relationshipType'];
            $tabel = null;

            $ret = $foreignClass::where($foreignKey, $data['id']);
			if ($relationshipType == "belongsTo") {
                $tesQuery = "SELECT * FROM " . $tabel . " WHERE id = " . $data[$foreignKey];
            }else {
                $tesQuery = "SELECT * FROM " . $tabel . " WHERE ".$foreignKey." = " . $data['id'];
            }
            $runQueryRelation = self::query($tesQuery);

            while ($row = mysqli_fetch_assoc($runQueryRelation)) {
                if ($relationshipType == "hasMany") {
                    if (array_key_exists('childRelation', $queue)) {
                        $childRelation = $queue['childRelation'];
                        $childClass = $childRelation['foreignClass'];
                        $childForeignKey = $childRelation['foreignKey'];

                        $tabel = null;
                        $ret = $childClass::where($childForeignKey, $row['id']);
                        $childQuery = "SELECT * FROM " . $tabel . " WHERE " . $childForeignKey . " = " . $row['id'];
                        $runChildQuery = self::query($childQuery);

                        if ($runChildQuery) {
                            if ($childRelation['relationshipType'] == "hasOne" || $childRelation == "belongsTo") {
                                $child = mysqli_fetch_assoc($runChildQuery);
                                $row[$queue['childRelation']['methodCaller']] = $child;
                            }else {
                                while ($child = mysqli_fetch_assoc($runChildQuery)) {
                                    $row[$queue['childRelation']['methodCaller']][] = $child;
                                }
                            }
                        }

                        // $row[$queue['childRelation']['methodCaller']] = $childDatas;
                        // echo $childDatas->toSql()."<br />";
                    }
                    $data[$queue['methodCaller']][] = $row;
                }else if ($relationshipType == "belongsTo" || $relationshipType == "hasOne") {
                    $data[$queue['methodCaller']] = $row;
                }
            }
        }
        return $data;
    }
    public function doRelationJob($data) {
        foreach (self::$queueWith as $row) {
            $queryWith = "SELECT * FROM $row[0] WHERE ";
            foreach ($row[1] as $key => $value) {
                $value = @$data[$value];
                if (preg_match("/'/", $value)) {
                    $quote = "'";
                    $value = str_replace("'", "''", $value);
                }else if (preg_match('/"/', $value)) {
                    $quote = '"';
                    $value = str_replace('"', '""', $value);
                }else {
                    $quote = "'";
                }
                $queryWith .= "$key = $quote$value$quote";
            }
            $data[$row[0]] = $this->query(self::$koneksi, $queryWith);
        }
        return $data;
    }
	public static function toObject($datas) {
		return json_decode(json_encode($datas), FALSE);
    }
    public static function checkIfTableStillNull() {
        global $tabel;
        if ($tabel == null) {
            self::$calledClass = get_called_class();
            Model::getTableName(self::$calledClass);
        }
    }
    public function get($toSelect = null) {
        global $query,$res,$withWith,$tabel;
        if (self::$koneksi == null) {
            self::koneksi();
        }
        self::checkIfTableStillNull();
        
        $kolom = func_get_args();
		$printCol = "";
		if (count($kolom) == 1) {
			$printCol = $kolom[0];
		}
		if (count($kolom) == 0) {
			$printCol = "*";
		}

		if (count($kolom) > 1) {
			$i = 0;
			foreach ($kolom as $key => $kol) {
				$separator = $i++ < count($kolom) - 1 ? "," : "";
				$printCol .= "$kol".$separator;
			}
        }
        
        $query = substr($query, 0, 0) . "SELECT $printCol FROM $tabel " . substr($query, 1);
        
        $runQuery = mysqli_query(self::$koneksi, $query);
        $res = [];
        while($data = mysqli_fetch_assoc($runQuery)) {
            // if (count(self::$queueWith) != 0) {
            //     $data = $this->doRelationJob($data);
            // }
            if (count(self::$newQueueWith) != 0) {
                $data = $this->doRelationJobNew($data);
            }
            $res[] = $data;
        }
        
        self::$queueWith = [];
        $query = "";
        self::$collections = self::toObject($res);
        return self::toObject($res);
    }
    public function paginate($rows) {
        global $query,$res,$withWith,$paginateTotalPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page > 1) ? ($page * $rows) - $rows : 0;
        $total = mysqli_num_rows(mysqli_query(self::$koneksi, $query));
        $paginateTotalPages = ceil($total / $rows);

        if ($this->isContain("LIMIT", $query)) {
            $oldLim = explode("LIMIT", $query)[1];
            $query = str_replace(["LIMIT", $oldLim], ["LIMIT", " $start, $rows"], $query);
        }else {
            $query .= " LIMIT $start, $rows";
        }

        for ($i = 1; $i <= $paginateTotalPages; $i++) {
            array_push(self::$paginationLinks, "<a href='?page=$i'>$i</a>");
        }

        // self::$collections = self::toObject($res);
        // return self::$collections;
        return $this;
        // return self::toObject($res);
    }
    public function links() {
        $page = @$_GET['page'] != "" ? $_GET['page'] : 1;
        $prev = $page - 1;
        $next = $page + 1;

        if (count(self::$paginationLinks) > 1) {
            echo "<div class='pagination_links'>";
            if ($prev >= 1) {
                echo "<a href='".base_url().route()."?page=".$prev."'><div class='items'><i class='fas fa-angle-left'></i></i></div></a>";
            }
            for ($i = 1; $i <= count(self::$paginationLinks); $i++) {
                if ($page != $i) {
                    echo "<a href='".base_url().route()."?page=$i'><div class='items'>$i</div></a>";
                }else {
                    echo "<div class='items active'>$i</div>";
                }
            }
            if ($next <= count(self::$paginationLinks)) {
                echo "<a href='".base_url().route()."?page=".$next."'><div class='items'><i class='fas fa-angle-right'></i></i></div></a>";
            }
            echo "</div>";
        }
    }
    
    public function withOld($table, $fk) {
        global $res,$withWith;
        array_push(self::$queueWith, [$table, $fk]);
        return $this;
    }
    public function with($relationName) {
        self::checkIfTableStillNull();
        if (is_array($relationName)) {
            $historyRelation = [];
            foreach ($relationName as $relationA) {
                // $toPush = self::$calledClass::$relation();
                // array_push(self::$newQueueWith, $toPush);
                $relations = explode(".", $relationA);
                if (array_key_exists(1, $relations)) {
                    $i = $historyIndex = 0;
                    foreach ($relations as $rel) {
                        // echo $i;
                        if ($i == 0) {
                            $relation = self::$calledClass::$rel();
                            if ($relations[$i + 1] != "") {
                                $childRelationName = $relations[$i + 1];
                                $foreignClass = $relation['foreignClass'];
                                $childRelation = $foreignClass::$childRelationName();
                                $relation['childRelation'] = $childRelation;
                            }
                            $historyRelation = $relation;
                            $i++;
                        }
                    }
                }else {
                    $relation = self::$calledClass::$relationA();
                    $historyRelation = $relation;
                }
                array_push(self::$newQueueWith, $historyRelation);
            }
        }else {
            $relations = explode(".", $relationName);
            if (array_key_exists(1, $relations)) {
                $i = $historyIndex = 0;
                $historyRelation = [];
                foreach ($relations as $rel) {
                    if ($i == 0) {
                        $relation = self::$calledClass::$rel();
                        if ($relations[$i + 1] != "") {
                            $childRelationName = $relations[$i + 1];
                            $foreignClass = $relation['foreignClass'];
                            $childRelation = $foreignClass::$childRelationName();
                            $relation['childRelation'] = $childRelation;
                        }
                        array_push($historyRelation, $relation);
                        $i++;
                    }
                }
                self::$newQueueWith = $historyRelation;
            }else {
                $relation = self::$calledClass::$relationName();
                array_push(self::$newQueueWith, $relation);
            }
        }
        
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function find($id) {
        global $query,$tabel;
        $query .= "SELECT * FROM $tabel WHERE id = '$id'";
        $runQuery = mysqli_query(self::$koneksi, $query);
        $data = mysqli_fetch_assoc($runQuery);
        if (!$data) return false;
        return self::toObject($data);
    }

    public function increment($column, $counter = 1) {
        global $query,$tabel;

        $query = substr($query, 0, 1) . "$column = $column + $counter " . substr($query, 1);
        $query = substr($query, 0, 0) . "UPDATE $tabel SET " . substr($query, 1);
        
        return mysqli_query(self::$koneksi, $query);
    }
    public function decrement($column, $counter = 1) {
        global $query,$tabel;

        $query = substr($query, 0, 1) . "$column = $column - $counter " . substr($query, 1);
        $query = substr($query, 0, 0) . "UPDATE $tabel SET " . substr($query, 1);
        
        return mysqli_query(self::$koneksi, $query);
    }
}
