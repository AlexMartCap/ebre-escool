SELECT person_locality_id,person_locality_name, state, postalcode, count( postalcode ) AS total
FROM person
GROUP BY person_locality_id,person_locality_name, state, postalcode
ORDER BY `total` DESC
LIMIT 0 , 300