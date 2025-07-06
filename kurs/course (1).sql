-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 20 2025 г., 11:30
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `course`
--

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lid` varchar(255) NOT NULL,
  `content` varchar(2048) NOT NULL,
  `rubric_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` datetime NOT NULL,
  `capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `title`, `lid`, `content`, `rubric_id`, `image`, `date`, `capacity`) VALUES
(14, 'Уроки французкого', 'Урок французского посвящен основам языка, включая алфавит, произношение и базовые фразы.', 'Вот краткое описание для урока французского языка:\r\n\r\nУрок французского посвящен основам языка, включая алфавит, произношение и базовые фразы. Мы изучим основные грамматические конструкции, такие как местоимения и глаголы в настоящем времени. Также рассмотрим повседневные темы, например, знакомства, приветствия и описания личных интересов. Урок включает практические упражнения и диалоги для улучшения навыков разговорной речи.', 2, 'images/france.jpg', '2025-07-17 07:28:00', 2),
(15, 'Уроки немецкого', 'Урок немецкого языка охватывает базовые элементы общения, включая алфавит, произношение и простые фразы.', 'Урок немецкого языка охватывает базовые элементы общения, включая алфавит, произношение и простые фразы. Мы изучим основные грамматические правила, такие как артикли, местоимения и глаголы в настоящем времени. Также будет уделено внимание теме повседневного общения, включая приветствия, представления и обсуждения интересов. Урок включает упражнения на чтение и разговорную практику для закрепления новых знаний.', 3, 'images/german.jpg', '2025-05-22 23:04:00', 20),
(16, 'Урок Китайского', 'Уроки китайского языка направлены на изучение пиньиня, иероглифов и основ грамматики.', 'Мы познакомимся с основными фразами для повседневного общения, такими как приветствия, знакомства и выражение вежливости. Учебный процесс включает слушание и повторение для улучшения произношения, а также разговорные практики для работы над навыками общения. Кроме того, занятия будут включать культурные аспекты Китая, чтобы лучше понять язык и традиции.', 4, 'images/china.png', '2025-05-09 12:34:00', 3),
(18, 'Уроки Английского', 'На уроках английского языка вы сможете изучить базовую грамматику, отточить произношение и узнаете самые необходимые фразы.', 'На уроках английского языка вы сможете изучить базовую грамматику, отточить произношение и узнаете самые необходимые фразы.', 1, 'images/eng.jpg', '2025-07-17 00:00:00', 20),
(24, 'test', 'test', 'asdsad', 8, 'images/EXpaBxI4r64M9JyEvR3GmKoBOUCHddGYHy9ttoGx.jpg', '2025-06-27 00:00:00', 22);

-- --------------------------------------------------------

--
-- Структура таблицы `course_users`
--

CREATE TABLE `course_users` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `FIO` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `city` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `course_users`
--

INSERT INTO `course_users` (`id`, `user_id`, `course_id`, `FIO`, `age`, `city`, `created_at`, `updated_at`) VALUES
(23, 14, 16, 'Иванов Иван Иванович', 22, 'Краснодар', '2025-06-19 12:53:16', '2025-06-20 08:06:25'),
(24, 15, 15, 'Иванов Иван Иванович', 56, 'Когалым', '2025-06-19 13:04:29', '2025-06-20 08:06:43');

-- --------------------------------------------------------

--
-- Структура таблицы `rubrics`
--

CREATE TABLE `rubrics` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `rubrics`
--

INSERT INTO `rubrics` (`id`, `name`) VALUES
(1, 'Английский'),
(2, 'Французский'),
(3, 'Немецкий'),
(4, 'Китайский'),
(8, 'zxc');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `login`, `password`, `image`, `admin`) VALUES
(1, 'Админ', 'admin@gmail.com', 'admin', '$2y$12$GD9oMd6juec7s89fMO/7QOOCWNE81iZ6SK13atfBFFTBM4fs4ZB/a', 'images/admin.jpeg', 1),
(13, 'а а а', '3423@4.g', 'sdf', '$2y$12$7hN56d.EG9eElK2ugAwJceWT0GnbSQ.8Hfv5rJHrKSZLCYukgDy8W', 'images/BZqEfnVVndBatQ8XEshzRDHYJIDbItYBbkH1ceai.jpg', 0),
(14, 'фыв фыв фыв', 'test@test.test', 'test', '$2y$12$5NcDaryZRu.gvxg5BODB..cdQHjfZMh2dHrQtPjJlJt1mxR7PFHUW', 'images/da7rYQDROYK6Mx6gp2aoRDENkWgKlnf5d4YRXfZP.png', 0),
(15, 'фыв фыв фыв', 'test1@test.test', 'Test1', '$2y$12$ponMDaQ8/4CVxv5zhkDWAu.oaIgpxpsnjC5lmaAM8Qe0sVgyvh3Xu', 'images/piH56uMelesm5foTL5i2qSf6HbOVyIVRbLJ7kQxN.jpg', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubric_id` (`rubric_id`);

--
-- Индексы таблицы `course_users`
--
ALTER TABLE `course_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `rubrics`
--
ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `course_users`
--
ALTER TABLE `course_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `rubrics`
--
ALTER TABLE `rubrics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `course_users`
--
ALTER TABLE `course_users`
  ADD CONSTRAINT `course_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `course_users_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
