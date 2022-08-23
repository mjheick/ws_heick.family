# Project: Family Tree

Lets make a Family Tree.

# family database table

Every person gets stored the same way:

- id: a unique identifier, probably some number, that references this specific record
- name: No need to separate out first/mid/last. Just store the persons name
- parent-bio-x: Index to one of the two biological parents of this person
- parent-bio-y: Index to one of the two biological parents of this person
- parent-adopt-x: Index to one of the two adopted parents of this person
- parent-adopt-y: Index to one of the two adopted parents of this person
- partner: Index to the partner to create step-relationships
- dob: A date of birth. This would be a string but in yyyy-mm-dd format, and 00 or 0000 would signify initialization
- dod: A date of death. defaults to 0000-00-00, same format as dob

# TODO

- uploading and tagging images/video
- "text" field per person for...information?...
