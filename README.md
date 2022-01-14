# Project: Family Tree

Simply need a simple way to display a simple family tree.

# Storage Tables

This is how we plan to store all the data we need to make sure this is easy to navigate

## family

Things we need to store in the database per-person would be:

- id: a unique identifier, probably some number, that references this specific record
- name: No need to separate out first/mid/last. Just store the persons name
- parent-x: Index to one of the two parents of this person
- parent-y: Index to one of the two parents of this person
- partner: Index to the partner to create step-relationships
- dob: A date of birth. This would be a string but in yyyy-mm-dd format, and 00 or 0000 would signify initialization
- dod: A date of death. defaults to 0000-00-00, same format as dob
