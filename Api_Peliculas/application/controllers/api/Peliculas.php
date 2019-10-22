<?php

require APPPATH. 'libraries/Rest_Controller.php';

//Clase controladora
class Peliculas extends REST_Controller{
   public function __construct(){
       parent::__construct('rest');
       header('Access-Control-Allow-Origin: *'); 
       header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Header-Method'); 
       header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); 
       header('Alow: GET, POST, OPTIONS, PUT, DELETE'); 
   }

   public function index_options(){
       return $this->response(NULL, REST_Controller::HTTP_OK);
   }

   //Metodo para obtener registros de la base de datos
   public function index_get($id=null){
       if (!empty($id)) {
           $data = $this->db->get_where('peliculas', ['id_peliculas'=>$id])->row_array();
           if ($data === null) {
               $this->response("El registro con id $id no existe", REST_Controller::HTTP_NOT_FOUND);
           }
       }else {
           $data = $this->db->get('peliculas')->result();
       }
       $this->response($data, REST_Controller::HTTP_OK);
   }

   //Metodo para ingresar un nuevo registro
   public function index_post(){
       $data = [
           'titulo'=>$this->post('titulo'),
           'descripcion'=>$this->post('descripcion'),
           'director'=>$this->post('director'),
           'duracion'=>$this->post('duracion')
       ];

       $this->db->insert('peliculas', $data);
       $this->db->select_max('id_peliculas');
       $this->db->from('peliculas');
       $query = $this->db->get()->row_array();
       $query = $this->db->get_where('peliculas', ['id_peliculas'=>$query['id_peliculas']])->result();
       $this->response($query, REST_Controller::HTTP_CREATED);
   }

   //Metodo para actualizar un registro
   public function index_put($id){
       $data = $this->put();
       $this->db->update('peliculas', $data, array('id_peliculas'=>$id));
       $this->response('El registro fue actualizado con exito', REST_Controller::HTTP_OK);
   }

   //Metodo para eliminar un ergistro de la base de datos
   public function index_delete($id){
       $this->db->delete('peliculas', array('id_peliculas'=>$id));
       $this->response('El registro fue eliminado con exito', REST_Controller::HTTP_OK);
   }
}

?>