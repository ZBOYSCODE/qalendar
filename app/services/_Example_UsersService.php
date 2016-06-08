<?php
namespace Gabs\Services;
use Gabs\Models\Grupo;
use Gabs\Models\HabilidadUserEvaluacion;
use Gabs\Models\UserHabilidad;
use Gabs\Models\Users;
use Gabs\Models\UserGrupo;

// Ejemplo Clase Service
// Usar nombre de modelo y concatenar a 'Service'
class UsersService
{
	public function getCantidadGrupos($id)
	{
		return count(Users::findFirst($id)->userGrupo);
	}

	public function getUserById($id)
	{
		return Users::findFirst($id);
	}

	public function getUsersByGrupo($id)
	{
		return Grupo::findFirst($id)->userGrupo->user;
	}

	public function getCantidadReconocimientosByUser($id)
	{
		return count(HabilidadUserEvaluacion::find("user_evaluado_id = {$id}"));
	}	

	public function getCantidadHabilidadesByUser($id)
	{
		return count(UserHabilidad::find("user_id = {$id}"));
	}

}