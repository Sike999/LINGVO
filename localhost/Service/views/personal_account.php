<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php"); 
        exit();
    }

    $userId = $_SESSION['user_id'];
    $userFio = $_SESSION['user_fio'];
    $userEmail = $_SESSION['user_email'];

    include '../functions/connect_db.php';

    $messages = ""; 

    try {
        if (isset($_POST['cancel_appointment_id'])) {
            $appointmentId = $_POST['cancel_appointment_id'];

            $stmt = $pdo->prepare("SELECT appointment_date FROM appointments WHERE id = :appointment_id AND user_id = :user_id");
            $stmt->bindParam(':appointment_id', $appointmentId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($appointment) {
                $appointmentDate = new DateTime($appointment['appointment_date']);
                $currentDate = new DateTime();

                $interval = $currentDate->diff($appointmentDate);
                if ($interval->d >= 1) {
                    $stmt = $pdo->prepare("UPDATE appointments SET cancelled_at = NOW() WHERE id = :appointment_id");
                    $stmt->bindParam(':appointment_id', $appointmentId);
                    $stmt->execute();

                    $stmt = $pdo->prepare("UPDATE available_slots SET available_slots = available_slots + 1 WHERE doctor_id = (SELECT doctor_id FROM appointments WHERE id = :appointment_id) AND available_date = :appointment_date");
                    $stmt->bindParam(':appointment_id', $appointmentId);
                    $stmt->bindParam(':appointment_date', $appointment['appointment_date']);
                    $stmt->execute();

                    $messages .= "<p class='messages'>Запись успешно отменена!</p>";
                } else {
                    $messages .= "<p class='messages'>Вы не можете отменить запись на этот прием, так как до него осталось менее 1 дня</p>";
                }
            } else {
                $messages .= "<p class='messages'>Запись не найдена</p>";
            }
        }

        $stmt = $pdo->prepare("
            SELECT 
            appointments.id AS appointment_id, 
            appointments.appointment_date, 
            doctors.fio AS doctor_fio, 
            doctors.specialization AS doctor_specialization
            FROM appointments
            JOIN doctors ON appointments.doctor_id = doctors.id
            WHERE appointments.user_id = :user_id AND appointments.cancelled_at IS NULL
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($appointments) > 0) {
            $messages .= "<table>
                <thead>
                    <tr>
                        <th>Доктор</th>
                        <th>Специальность</th>
                        <th>Дата приема</th>
                        <th>Отмена</th>
                    </tr>
                </thead>
                <tbody>";

            foreach ($appointments as $appointment) {
                $messages .= "<tr>
                    <td>" . htmlspecialchars($appointment['doctor_fio']) . "</td>
                    <td>" . htmlspecialchars($appointment['doctor_specialization']) . "</td>
                    <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='cancel_appointment_id' value='" . htmlspecialchars($appointment['appointment_id']) . "'>
                            <button type='submit' onclick='return confirm(\"Вы уверены, что хотите отменить запись?\");'>Отменить</button>
                        </form>
                    </td>
                </tr>";
            }

            $messages .= "</tbody>
            </table>";
        } else {
            $messages .= "<p>У вас нет активных записей на прием</p>";
        }
    } catch (PDOException $e) {
        $messages .= "<p>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
?>

<link rel="stylesheet" href="../assets/styles/personal_account.css">

<header class="header">
    <a class="header__home" href="../views/main.php"><img class="header__logotype" src="../assets/images/logo.png"></a>
</header>

<div class="container">
<aside class="sidebar">
    <nav class="sidebar__navigation">
		<ul class="sidebar__list">
			<li class="sidebar__item"><a class="sidebar__link" href="../views/personal_account.php">Мои записи</a></li>
			<li class="sidebar__item"><a class="sidebar__link" href="../views/doctor_appointment.php">Записаться на прием</a></li>
		</ul>
	</nav>

    <section>
        <div class="account__info">
            <img class="account_img" src="../assets/images/profile.png" alt="">
            <div class="account__name">
                <p class="account__fio"><?= htmlspecialchars($userFio); ?></p>
                <p class="account__login"><?= htmlspecialchars($userEmail); ?></p>
            </div>
        </div>

        <a class="button--logout" href="../functions/logout.php">Выйти</a>
    </section>    
</aside>

    <main class="main">
        <h2 class="main__title">Мои записи на прием</h2>
        <section>
            <?= $messages; ?>
        </section>
    </main>
</div>
