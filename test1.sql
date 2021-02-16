
-- Βάση δεδομένων: `test1`
--
CREATE DATABASE test1;
-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `bookings`
--

CREATE TABLE `bookings` (
  `bookid` int(6) NOT NULL,
  `custid` int(6) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `band_name` varchar(40) NOT NULL,
  `diazoma` varchar(20) NOT NULL,
  `seats` int(40) NOT NULL,
  `date` varchar(20) NOT NULL,
  `cost` int(25) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `concerts`
--

CREATE TABLE `concerts` (
  `concert_ID` int(10) NOT NULL,
  `band_name` varchar(45) NOT NULL,
  `date` varchar(40) NOT NULL,
  `place` varchar(40) NOT NULL,
  `doors_open` varchar(25) NOT NULL,
  `ticket_price` int(25) NOT NULL,
  `remaining_tickets` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `concerts`
--

INSERT INTO `concerts` (`concert_ID`, `band_name`, `date`, `place`, `doors_open`, `ticket_price`, `remaining_tickets`) VALUES
(100, 'Metallica', '15/07/2018', 'Athens(OAKA Stadium)', '21:00', 35, 12),
(101, 'Sting', '13/08/2018', 'Odeon Herodes Atticus', '20:00', 40, 20),
(102, 'Scorpions', '20/09/2018', 'Karaiskaki Stadium(Neo Faliro)', '19:00', 30, 20),
(103, 'ACDC', '23/10/2018', 'Tae Kwon Do(Palaio Faliro)', '18:00', 50, 20),
(104, 'James Blunt', '21/11/2018', ' Nerou Square(Palaio Faliro)', '19:00', 30, 20),
(105, 'Lana Del Rey', '01/12/2018', 'Terra Vibe', '20:00', 50, 14),
(106, 'The Killers', '10/12/2018', 'OAKA(Basket Stadium)', '20:00', 50, 5),
(107, 'Enrique Iglesias', '22/12/2018', 'Tae Kwon Do(Palaio Faliro)', '19:00', 45, 10);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `age` int(5) NOT NULL,
  `city` varchar(20) NOT NULL,
  `address` varchar(20) NOT NULL,
  `tk` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `balance` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `customers`
--

INSERT INTO `customers` (`id`, `name`, `surname`, `age`, `city`, `address`, `tk`, `email`, `phone`, `balance`) VALUES
(1, 'nick', 'gav', 55, 'we', 'we', '18537', 'nikosgavalas1990@gmail.com', '6956472752', 200),
(2, 'nick gav', 'gav', 55, 'we', 'we', '18537', 'nikosgavalas1990@gmail.com', '6956472752', 200),
(3, 'paulina fragkou', 'fragkou', 28, 'athens', 'ag spuridonos 25', '13456', 'nikosgavalas1990@gmail.com', '6956472722', 300);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `login`
--

CREATE TABLE `login` (
  `username` varchar(45) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `failed_logins` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(10) NOT NULL DEFAULT 'UNLOCKED',
  `id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `login`
--

INSERT INTO `login` (`username`, `pass`, `failed_logins`, `last_activity`, `status`, `id`) VALUES
('we', 'we', 0, '2018-06-29 13:49:36', 'UNLOCKED', 1),
('aa', 'aa', 0, '2018-06-29 13:49:48', 'UNLOCKED', 2),
('fragkou', '1234', 0, '2018-06-29 13:57:24', 'UNLOCKED', 3);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookid`),
  ADD KEY `custid` (`custid`);

--
-- Ευρετήρια για πίνακα `concerts`
--
ALTER TABLE `concerts`
  ADD PRIMARY KEY (`concert_ID`);

--
-- Ευρετήρια για πίνακα `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT για πίνακα `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `login`
--
ALTER TABLE `login`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `custid` FOREIGN KEY (`custid`) REFERENCES `customers` (`id`);

--
-- Περιορισμοί για πίνακα `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
