<?php 
    include '../functions/connect_db.php';

    $messages = [];

    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE 'doctors'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $doctorsFound = false; 
        } else {
            $stmt = $pdo->prepare("SELECT DISTINCT specialization FROM doctors");
            $stmt->execute();
            $specializations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $doctorsFound = true; 
        }
    } catch (PDOException $e) {
        $messages[] = 'Ошибка: ' . $e->getMessage();
        $doctorsFound = false; 
    }
?>

<link rel="stylesheet" href="../assets/styles/doctors.css">

<div class="container">
    <?php
        include '../includes/header.php';
    ?>

    <main class="page__content">
        <h1 class="doctors__title">Наши врачи</h1>

        <section class="page__section">
            <img class="section--img" src="../assets/images/team.jpg">
        </section>

        <?php if (!$doctorsFound) { ?>
            <p class="message">Врачи не найдены</p>
        <?php } else { ?>
            <?php
                $content = '';

                foreach ($specializations as $specialization) {
                    $specializationName = $specialization['specialization'];

                    $content .= "<h2 class='specialization__title'>" . htmlspecialchars($specializationName) . "</h2>";

                    try {
                        $stmt = $pdo->prepare("SELECT fio FROM doctors WHERE specialization = :specialization");
                        $stmt->execute(['specialization' => $specializationName]);
                        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($doctors) {
                            $content .= "<ul class='doctor__list'>";
                            foreach ($doctors as $doctor) {
                                $content .= "<li class='doctor__item'>" . htmlspecialchars($doctor['fio']) . "</li>";
                            }
                            $content .= "</ul>";
                        } else {
                            $content .= "<p class='message'>Врачи не найдены</p>";
                        }
                    } catch (PDOException $e) {
                        $content .= "Ошибка: " . $e->getMessage();
                    }

                    $conn = null;
                }
            ?>
            <?= $content ?>
        <?php } ?>
    </main>
    <?php 
        include '../includes/footer.php';
    ?>
</div>