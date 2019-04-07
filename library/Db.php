<?php
/*Класс обертка для работы с базой*/
class Db
{
	private $db;// приватное свойство для хранения экземпляра класса mysqli
	private $result;// результат запроса к базе
	public $error = false; // для проверки наличия ошибок после запроса
	public $sql_query;// последний sql запрос сделанный к базе
	
	public function __construct()//В конструкторе устанавливаем соединение с базой
	{
		$host = '127.0.0.1'; // место где хранится сервер базы данных
		$user = 'root'; // пользователь базы данных
		$password = ''; // пароль пользователя
		$db_name = 'example_db'; // название базы
		/*
			Создаем экземпляр класса mysqli для работы с базой и передаем параметры в конструктор
		*/
		$this->db = new \mysqli($host, $user, $password, $db_name);
	}

    /*Метод позволяет делать любые запросы к базе данных. В качестве параметра принимает sql запрос*/
	public function setQuery($sql)
	{
		$this->sql_query = $sql; // сохраняем запрос в переменную
		$this->result = $this->db->query($sql); // запрос к базе. вернёт объект mysqli_result
		$this->error = $this->result === false ? true : false; // проверяем ошибки
	}

	/*После исполнения sql запроса типа Select нужно получить массив данных, возвращаемых базой. Если передан параметр $limit = 1, то в ответ придет объект одной строки, иначе будет массив объектов*/
	public function getObject($limit = 0)
	{
		$out = array();
		if ($limit == 1) { // если $limit = 1 то возвращаем объект
			return $this->result->fetch_object();
		}
		// $this->result->fetch_object() возвращает либо строку из набора данных, извлеченных из базы, либо false если достигнут конец выборки
		while ($row = $this->result->fetch_object()) {
			$out[] = $row; // сохраняем объект в результирующий массив
		}
		return $out; 
	}

	/*возвраащет id последней добавленной записи*/
	public function lastId()
    {
        return $this->db->insert_id; 
    }

    /*Возвращает количество строк в выборке при запросе типа Select*/
	public function getNumRows()
	{
		return $this->result->num_rows; // у объекта mysqli_result есть свойство num_rows - получает число рядов в результирующей выборке
	}

	/*закрывает соединение с базой*/
	public function close()
	{
		$this->db->close();
	}
}
