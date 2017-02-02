# Plank


#### Welcome to Plank

This proyect is in development and increasing its functionalities. In its latest update incorporates "collections" that are a simple way to generate an api rest by declaring its structure in a file. The structure duplicate itself, update and do back ups in the database.

## New features
A collection automatically creates an api rest. For this is necessary to configure the connection to the database.

The configuration file is application/config/database.php
```php
//file: application/collections/example.php
class Example extends Collection
{
    public function __construct()
    {
        parent::__construct();
        //with this structure you can build a rest api
        $this->structure = [
          'fullname' => 'text',
          'date2' => 'timestamp',
          'lastname' => 'text'
        ];
    }
}
```
The api explose in yourdomain.example/api/:collection
#### Create record in database:
```
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=asdasd' "http://localhost/plank/api/example/"
```
Response:
```json
{
  "success": true,
  "data": {
    "fullname": "pepino",
    "date2": "2017-02-02 15:36:59",
    "lastname": "asdasd",
    "_id": "3"
  }
}
```
#### Get all record in database:
```
curl -X GET -H "Content-Type: application/x-www-form-urlencoded" "http://localhost/plank/api/example/"
```
Response:
```json
{
  "success": true,
  "data": [
    {
      "fullname": "nikola tesla",
      "date2": "2017-02-02 12:33:25",
      "lastname": "tesla",
      "_id": "1"
    },
    {
      "fullname": "max planck",
      "date2": "2017-02-02 12:33:52",
      "lastname": "planck",
      "_id": "2"
    },
    {
     "fullname": "pepino",
     "date2": "2017-02-02 15:36:59",
     "lastname": "asdasd",
     "_id": "3"
    }
  ]
}
```
#### Update Record:
```
curl -X PUT -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=plank' "http://localhost/plank/api/example/3"
```
Response:
```json
{
  "success": true,
  "data": {
    "fullname": "pepino",
    "date2": "2017-02-02 15:36:59",
    "lastname": "plank",
    "_id": "3"
  }
}
```
### Delete Record:
```
curl -X DELETE -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=plank' "http://localhost/plank/api/example/3"
```
Response:
```json
{
  "success": true,
  "message": "Document Deleted."
}
```
### Create a Collection:
A collection au
### Use (MVC old functionality)
### Create Controller:

Within the application / folder you can create a file with the name of the controller you want to create, the same controller execute when you enter http: //url.axample/index.php/:controllerName.
The driver must have the basic format:

```php
    //file: application/controller/example.php
	class Example extends Controller{
		function __construct(){
			parent::__construct();
		}
		public function index(){
			$this->load->view("inicio/index");
		}
	}
```

### Upload Views and Templates

Within each controller is possible to load views and models using the method load:

```php
    //load view in folder application/views
	$this->load->view("index");

	//load model in folder application/model
	$this->load->model("login_model");
```

### Data in views:
Views can receive parameters from the controller passing an associative array to the function:

```php
	$data["subscribers"] = 2000;
	$this->load->view("examples/index",$data);
```
This is in the view as a normal variable
```html
	<?php echo $cantidad;?>
```
### Templates for views:
To create templates we must create a file inside the application/view/template folder and within declare an array of views to load. The name view will be replace with the current dinamic view:
```php
		$template=array("index/header","view","index/footer");
```
Each element of the array is a view, to load a template we use the method load:
```php
    //in example controller
    //the first argument is the view and the second is the template
	$this->load->template("example","some_view");
	//or with some data
	$data["subscribers"] = 2000;
	$this->load->template("example", "some_view", $data);
```

### Create a Model:


To create a model is necessary to configure the database file in application/config/database.php
After properly putting the data for the connection we create the model:
```php
	Class Login_model extends Model{
		function __construct(){
			parent::__construct();
		}
		function buscar usuarios($usuarios){
			$usuarios = $this->db->new_query("SELECT * FROM users");
			return $this->db->fetch_all($usuarios);//metod creado para hacer un fetch all usando mysqli
		}
	}
```
