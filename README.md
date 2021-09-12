# Project: Family Tree

Simply need a simple way to display a simple family tree.

# Individual Storage

Things we need to store in the database per-person would be:

- id: a unique identifier, probably some number, that references this specific record
- fullname: No need to separate out first/mid/last. Just store the persons name
- dob: A date of birth. This would be a string but in yyyy-mm-dd format, and 00 or 0000 would signify initialization
- dod: A date of death. defaults to 0000-00-00, same format as dob
- father: The id of the biological/xy genetic donor of this person.
- mother: The id of the biological/xx genetic donor of this person.

Notes:
- Parents do not need to exist before entering the individual. Can be tied in later on.
- Minimum amount of information needed would be a fullname and the yyyy portion of dob or dod

# Matrimonial Storage

Another thing we will need to store is legal marriage/divorce as best as possible. This creates a step-siblings relationship that will not be initially displayed but will be implicitly comprehended by someone looking at things. All people needed to create a matrimonial relationship will need to be present before the connections can be made.

The naming of the fields for this is difficult since there is no easy way to determine who is what in a wedding, especialy since a person and an inanimate object can be legally bound nowadays. For simplicity and to prevent future controversy, party-\[left|right\] is what is going to be used

- id: A unique identifier
- party-left: One member of the marriage
- party-right: Second member of the marriage
- dom: Date of "legal" marriage
- dod: Date of "legal" divorce

# Multimedia Storage

Every individual can have multimedia attached to them. The standard created here prevents a "social media" approach to this and creates an "archival" approach, as we only would like one image/video per year of the individuals life.

It is possible that an image/video can represent multiple individuals, which is where some exceptions can be made. As long as it's not filed under the "other peoples" records then multiple records can exist, and we need to be able to allow this to happen as well as allow it to be findable and displayable with ease.

For each multimedia entry:
- id: A unique identifier
- filename: The name of the file that was upload
- mediatype: Image|Video
- created: When this multimedia record was birthed

For each person that multimedia is filed under:
- id: A unique identifier
- individual: The individuals ID of where this multimedia is filed under
- order: =0, this is the individuals. >0, there are other people present in this record 


