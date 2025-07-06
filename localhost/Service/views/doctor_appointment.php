<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $userFio = $_SESSION['user_fio'];
    $userEmail = $_SESSION['user_email'];

    include '../functions/connect_db.php';

    $messages = [];
    $specialists = [];
    $availableDoctors = [];
    $selectedSpecialization = '';
    $selectedDate = '';
    $specializationError = ''; 
    $dateError = ''; 

    try {
        $stmt = $pdo->query("SELECT DISTINCT specialization FROM doctors");
        $specialists = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST['specialization'])) {
                $selectedSpecialization = $_POST['specialization'];

                if (!in_array($selectedSpecialization, $specialists)) {
                    $specializationError = 'Выбранной специальности не существует';
                }
            }
            if (isset($_POST['appointment_date'])) {
                $selectedDate = $_POST['appointment_date'];

                $dateFormat = 'Y-m-d';
                $date = DateTime::createFromFormat($dateFormat, $selectedDate);
                if ($date && $date->format('Y-m-d') === $selectedDate) {
                    $currentDate = new DateTime();  
                    $currentDate->setTime(0, 0, 0); 

                    if ($date < $currentDate) {
                        $dateError = 'Дата должна быть не меньше сегодняшней';
                    }                         

                } else {
                    $dateError = 'Дата должна быть в формате ГГГГ.ММ.ДД';
                }
            }

            if (!empty($selectedSpecialization) && !empty($selectedDate)) {
                $stmt = $pdo->prepare("
                    SELECT fio, 
                        COALESCE(available_slots, 0) AS available_slots, 
                        doctors.id
                    FROM doctors
                    LEFT JOIN available_slots 
                    ON doctors.id = available_slots.doctor_id 
                    AND available_slots.available_date = :selected_date
                    WHERE specialization = :specialization
                ");

                $stmt->execute([
                    'specialization' => $selectedSpecialization,
                    'selected_date' => $selectedDate
                ]);
                $availableDoctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        if (isset($_POST['doctor_id'])) {
            $doctorId = $_POST['doctor_id'];
            $appointmentDate = $_POST['selected_date'];

            $stmt = $pdo->prepare("
                SELECT available_slots 
                FROM available_slots 
                WHERE doctor_id = :doctor_id AND available_date = :appointment_date
            ");
            $stmt->execute(['doctor_id' => $doctorId, 'appointment_date' => $appointmentDate]);
            $slots = $stmt->fetchColumn();

            if ($slots > 0) {
                $pdo->prepare("
                    UPDATE available_slots 
                    SET available_slots = available_slots - 1
                    WHERE doctor_id = :doctor_id AND available_date = :appointment_date
                ")->execute(['doctor_id' => $doctorId, 'appointment_date' => $appointmentDate]);

                $pdo->prepare("
                    INSERT INTO appointments (user_id, doctor_id, appointment_date)
                    VALUES (:user_id, :doctor_id, :appointment_date)
                ")->execute([
                    'user_id' => $_SESSION['user_id'],
                    'doctor_id' => $doctorId,
                    'appointment_date' => $appointmentDate
                ]);

                $messages[] = 'Вы успешно записались на прием!';
            } else {
                $messages[] = 'Свободных номерков на выбранную дату нет';
            }
        }
    } catch (PDOException $e) {
        $messages[] = 'Ошибка: ' . $e->getMessage();
    }
?>


<link rel="stylesheet" href="../assets/styles/doctor_appointment.css">

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
        <h2 class="main__title">Запись на прием</h2>

        <?php foreach ($messages as $message) { ?>
            <p class="message"><?= htmlspecialchars($message); ?></p>
        <?php } ?>

        <form method="POST" action="../views/doctor_appointment.php" class="form__appointment">
            <div class="form__box">
                <label class="form__label" for="specialization">Выберите специальность:</label>
                <select class="form__select" name="specialization" id="specialization" required>
                    <option value="">Выберите</option>
                    <?php foreach ($specialists as $specialization) { ?>
                        <option value="<?= htmlspecialchars($specialization); ?>" <?= $specialization === $selectedSpecialization ? 'selected' : '' ?>>
                            <?= htmlspecialchars($specialization); ?>
                        </option>
                    <?php } ?>
                </select>
                <?php if ($specializationError) { ?>
                    <p class="error"><?= htmlspecialchars($specializationError); ?></p>
                <?php } ?>
            </div>

            <div class="form__box">
                <label class="form__label" for="appointment_date">Выберите дату:</label>
                <input class="form__input" type="date" id="appointment_date" name="appointment_date" value="<?= htmlspecialchars($selectedDate); ?>" required>
                <?php if ($dateError) { ?>
                    <p class="error"><?= htmlspecialchars($dateError); ?></p>
                <?php } ?>
            </div>
            
            <div class="form__button">
                <button class="button--primary" type="submit">Показать врачей</button>
            </div>
        </form>

        <?php if (!empty($availableDoctors)) { ?>
            <ul>
                <?php foreach ($availableDoctors as $doctor) { ?>
                    <li class="doctor-item">
                        <section class="doctor__info">
                            <div class="doctor-fio"><?= htmlspecialchars($doctor['fio']); ?></div>
                            <div class="doctor-slots">Свободных мест: <?= $doctor['available_slots']; ?></div>
                            <?php if ($doctor['available_slots'] > 0) { ?>
                            <form method="POST" class="appointment-form">
                                <input type="hidden" name="doctor_id" value="<?= $doctor['id']; ?>">
                                <input type="hidden" name="selected_date" value="<?= htmlspecialchars($selectedDate); ?>">
                                <button class="appointment__button" type="submit">Записаться</button>
                            </form>
                            <?php } else { ?>
                                <p class="no-availability">Запись недоступна</p>
                            <?php } ?>
                        </section>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </main>
</div>
