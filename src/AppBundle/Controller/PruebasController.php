<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Curso;
use AppBundle\Form\CursoType;
use Symfony\Component\Validator\Constraints as Assert;

class PruebasController extends Controller
{
   
    public function indexAction(Request $request,$name,$page)
    {
        // return $this->redirect($request->getBaseUrl()."/hello-world?hola=true");
		
		$productos = array(
					array("producto"=>"Consola","precio"=>2),
					array("producto"=>"Consola 2","precio"=>3),
					array("producto"=>"Consola 3","precio"=>4),
					array("producto"=>"Consola 4","precio"=>5),
					array("producto"=>"Consola 5","precio"=>6)
			);
		
		$fruta=array("manzana"=>"golden","pera"=>"rica");
		
        return $this->render('AppBundle:Pruebas:index.html.twig', array(
            'texto' => $name." - ".$page,
			'productos' => $productos,
			'fruta' => $fruta
        ));
    }
	
	public function createAction(){
		
		$curso = new Curso();
		$curso->setTitulo("Curso de Symfony3 de Victor Robles");
		$curso->setDescripcion("Curso completo de Symfony3");
		$curso->setPrecio(80);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($curso);
		$flush=$em->flush();
		
		if($flush != null){
			echo "El curso no se ha creado bien!!";
		}else{
			echo "El curso se ha creado correctamente";
		}
		
		die();
	}
	
	public function readAction(){
		$em = $this->getDoctrine()->getEntityManager();
		$cursos_repo = $em->getRepository("AppBundle:Curso");
		$cursos = $cursos_repo->findAll();
		
		$curso_ochenta=$cursos_repo->findOneByPrecio(81);
		echo $curso_ochenta->getTitulo();
		
//		foreach($cursos as $curso){
//			echo $curso->getTitulo()."<br/>";
//			echo $curso->getDescripcion()."<br/>";
//			echo $curso->getPrecio()."<br/><hr/>";
//		}
		
		die();
	}
	
	public function updateAction($id, $titulo, $descripcion, $precio){
		$em = $this->getDoctrine()->getEntityManager();
		$cursos_repo = $em->getRepository("AppBundle:Curso");
		
		$curso = $cursos_repo->find($id);
		$curso->setTitulo($titulo);
		$curso->setDescripcion($descripcion);
		$curso->setPrecio($precio);
		
		$em->persist($curso);
		$flush=$em->flush();
		
		if($flush!=null){
			echo "El curso NO SE HA ACTUALIZADO!!";
		}else{
			echo "El curso se ha actualizado correctamente";
		}
		
		die();
	}
	
	public function deleteAction($id){
		$em = $this->getDoctrine()->getEntityManager();
		$cursos_repo = $em->getRepository("AppBundle:Curso");
		
		$curso = $cursos_repo->find($id);
		$em->remove($curso);
		
		$flush=$em->flush();
		
		if($flush!=null){
			echo "No se ha borrado bien!!";
		}else{
			echo "El curso se ha borrado correctamente";
		}
		
		die();
	}
	
	public function nativeSqlAction(){
		$em = $this->getDoctrine()->getEntityManager();
		$cursos_repo=$em->getRepository("AppBundle:Curso");
//		$db = $em->getConnection();
//		
//		$query = "SELECT * FROM cursos";
//		$stmt = $db->prepare($query);
//		$params = array();
//		$stmt->execute($params);
//		
//		$cursos = $stmt->fetchAll();
		
//		$query = $em->createQuery("
//			SELECT c FROM AppBundle:Curso c
//			WHERE c.precio > :precio
//			")->setParameter("precio", "79");
//		$cursos = $query->getResult();
		
		
		$cursos = $cursos_repo->getCursos();
		
		foreach($cursos as $curso){
			echo $curso->getTitulo()."<br/>";
		}
		
		die();
		
	}
	
	
	public function formAction(Request $request){
		$curso = new Curso();
		$form = $this->createForm(CursoType::class,$curso);
		
		$form->handleRequest($request);
		
		if($form->isValid()){
			$status = "Formulario válido";
			$data = array(
				"titulo" => $form->get("titulo")->getData(),
				"descripcion" => $form->get("descripcion")->getData(),
				"precio" => $form->get("precio")->getData()
			);
		}else{
			$status = null;
			$data = null;
		}
		
		return $this->render('AppBundle:Pruebas:form.html.twig', array(
            'form' => $form->createView(),
			'status' => $status,
			'data' => $data
        ));
	}
	
	public function validarEmailAction($email){
		$emailConstraint = new Assert\Email();
		$emailConstraint->message = "Pasame un buen correo";
		
		$error= $this->get("validator")->validate(
				$email,
				$emailConstraint
				);
		if(count($error)==0){
			echo "<h1>Correo valido!!</h1>";
		}else{
			echo $error[0]->getMessage();
		}
		die();
	}
}
