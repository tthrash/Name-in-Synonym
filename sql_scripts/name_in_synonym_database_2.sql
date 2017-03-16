use ics325;

ALTER TABLE puzzles
  ADD PRIMARY KEY (puzzle_name);

ALTER TABLE words
  ADD CONSTRAINT rep_id_fk FOREIGN KEY (rep_id) REFERENCES word (word_id) ON UPDATE CASCADE;

