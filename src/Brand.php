<?php
    class Brand
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;

        }

        function getName()
        {
            return $this->name;
        }
        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO brands (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()}");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE brand_id = {$this->getId()}");
        }

        function update($new_name)
        {
            $this->setName($new_name);

            $GLOBALS["DB"]->exec("UPDATE brands SET name = '{$this->getName()}' WHERE id = {$this->getId()};");
        }

        function addStore($id)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$this->getId()}, {$id});");
        }

        function getStores()
        {
            $returned_stores = $GLOBALS['DB']->query(
            "SELECT stores.* FROM brands
            JOIN brands_stores ON (brands_stores.brand_id = brands.id)
            JOIN stores ON (stores.id = brands_stores.store_id)
            WHERE brands.id = {$this->getId()};");

            return $returned_stores->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Store", ['name', 'id']);
        }

        static function find($id)
        {
            $returned_brand = $GLOBALS['DB']->query("SELECT * FROM brands WHERE id={$id};");
            return $returned_brand->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Brand", ['name', 'id'])[0];

        }

        static function getAll()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
            if($returned_brands){
                return $returned_brands->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Brand", ['name', 'id']);

            } else {
                return [];
            }
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands;");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores;");
        }
    }
?>
