<?php
class Messages_Model extends AModel {

	public function removeMessage($messagecontrol) {
		$sql = "DELETE FROM messages WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute(array($messagecontrol));
		return $this->fetchObjects($result);

	}
	public function insertMessage($data = array(),$messagecontrol) {
		$data[] = $messagecontrol;
		$sql = "INSERT INTO messages (subject,message,loggedtime,usercontrol) VALUES (?,?,?,?)";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result->insertId();

	}
	public function getMessages($messagecontrol) {
		$sql = "SELECT * FROM messages LEFT JOIN users ON(users.control = messages.usercontrol)";

		$statement = $this->prepare($sql);

		$statement->execute();

		return $this->fetchObjects($statement);

	}
	public function updateMessage($data = array(),$messagecontrol) {
		$data[] = $messagecontrol;
		$sql = "UPDATE messages SET WHERE control = ?";

		$statement = $this->prepare($sql);
		$result = $statement->execute($data);
		return $result;

	}
}
?>