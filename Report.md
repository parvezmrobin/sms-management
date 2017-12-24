# Business SMS Management

Software Engineering <br>
Parvez Mahbub <br>
Student ID: 150204

## Introduction

_Business SMS Management_ is a system where user can send SMS to receivers individually, grouped or providing an Excel sheet. The system will automatically manage the history and transactions. Additionally, there will be features to add, view, update, delete contacts or groups.

## Requirements

1. Send SMS
    1. To individuals
    2. To groups
    3. Reading Excel Sheet
    4. User will provide an Excel sheet, SMS format and select the contact number column in Excel sheet. SMSs will be generated accordingly and be sent to associated clients.
2. Reporting
    1. According to campaign [_Campaign:_ SMSs that are sent as a bunch]
    2. According to individual SMS
    3. Transactional Report including export as Excel Sheet.
    4. Each reporting system contains date range search and keyword search.
3. Contact
    1. Create, read, update, delete contacts including importing contacts from Excel.
    2. Create, read, update, delete groups including search facility.
4. Account Recharge
5. API Documentation
6. Change in Profile

## Modules

According to the requirements given, we can divide the system into following modules.

### Sending SMS

As the name suggests, _Sending SMS_ modules sends SMSs. This module depends on _User_ module for logged in user information and _Contact_ module for contact and group information. On the basis of sending technique, this can be divided into three sub modules.

#### Sending SMS to Individual

This sub module sends a static SMS to a number of contact numbers given in CSV form.

#### Sending SMS to Group

This sub module sends a static SMS to a number of groups of contacts selected from existing group list.

#### Sending SMS From Excel Sheet

This sub module sends a number of dynamic SMSs generated from a user given Excel sheet. Reading the Excel sheet, the system will offer user to select a specific sheet. Then he / she will be asked to select the column holding the numbers. The he / she has to provide an SMS format where a phrase inside double curly braces (e.g. {{Name}}) denote column name.

Provided the data, the system will send the SMSs accordingly.

### Reporting

The _Reporting_ module basically keeps track of sent SMSs and related transaction history. It has three types of reporting namely — Individual, Group and Transaction. Each kind contains search with date range and keyword.

Behind the scene, reporting module does a compressing task on Excel SMSs. Only the SMS format is stored and dynamic SMSs are regenerated before searching. Additionally, if an SMS is too big, it may be broken into two or more SMSs while sending. These are concatenated before searching operation.

This module depends on _Sending SMS_ module for SMS information.

### Contact

The _Contact_ module manages contacts and groups. This includes create, read, update and delete of both contacts and groups. This also provides adequate search facility.

### User

Although has less functionality, user module is the most important module in a sense that, all other modules depend on it for logged in user information. This module controls registration, logging in, password change and account recharge facility.

### Actors

The system has two actors — _User_ and _Business SMS Provider_.

The user is the main actor who interacts with the system graphically. He / She sends SMSs, checks history, manages contacts, recharges and manages his / her own account.

The _Business SMS Provider_ is not a human user but a third-party tool which receives an SMS and contact number as a request and sends SMS to the contact number providing confirmation response.

## Use Cases

As our one actor _Business SMS Provider_ is a third-party tool, it does not require any sort of testing. All of our use cases will be performed by user.

1. Register

    - **Primary Actor:** User (to be registered)
    - **Normal Flow**
      -  User fills the _Username_ field
      -  User fills the _Email to_ field
      -  Users fill the _Password_ field
      -  Users fill the _Confirm password_ field
      -  User clicks on _Register_ button
      -  User be registered
      -  User is redirected to home page
    - **Exception Flow**
      -  Username already exists

2. Login

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Email to_ field
      -  Users fill the _Password_ field
      -  User clicks on _Login_ button
      -  User be logged in
      -  User is redirected to home page
    - **Exception Flow**
      -  _Username_ &amp; _password_ combination does not match

3. Send SMS to Individual

    - **Primary Actor:** User
    - **Secondary Actor:** Business SMS Provider
    - **Normal Flow**
      -  User fills the SMS field
      -  User writes the contact numbers in CSV form
      -  User clicks on _Send_ button
      -  Data are sent to Business SMS Provider
      -  Business SMS Provider sends the messages
      -  Confirmation message is shown to User
      -  Balance is reduced accordingly
    - **Exception Flow**
      -  No SMS provided
      -  No contact number provided
      -  Contact number are not in correct format
      -  Business SMS Provider could not send SMS due to internal error

4. Send SMS to Group

    - **Primary Actor:** User
    - **Secondary Actor:** Business SMS Provider
    - **Normal Flow**

      -  User fills the SMS field
      -  User selects the contact group(s)
      -  User clicks on _Send_ button
      -  Data are sent to Business SMS Provider
      -  Business SMS Provider sends the messages
      -  Confirmation message is shown to User
      -  Balance is reduced accordingly
    - **Exception Flow**
      -  No SMS Provided
      -  No group selected
      -  Business SMS Provider could not send SMS due to internal error

5. Send SMS from Excel

    - **Primary Actor:** User
    - **Secondary Actor:** Business SMS Provider
    - **Normal Flow**
      -  User uploads an Excel sheet
      -  User selects the sheet number
      -  User selects contact number column
      -  User fills the SMS format field
      -  User clicks on _Send_ button
      -  Data are sent to Business SMS Provider
      -  Business SMS Provider sends the messages
      -  Confirmation message is shown to User
      -  Balance is reduced accordingly
    
    - **Exception Flow**
      -  No SMS Provided
      -  SMS ill-formatted
      -  Excel sheet corrupted
      -  Excel sheet ill-formatted
      -  Business SMS Provider could not send SMS due to internal error

6. Search Campaigns

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Date from_ field
      -  User fills the _Date to_ field
      -  Users fill the _Search keyword_ field
      -  User clicks on _Search_ button
      -  Campaign list is loaded
    - **Exception Flow**
      -  _Date to_ field is prior to _Date from_ field

7. Search SMSs

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Date from_ field
      -  User fills the _Date to_ field
      -  Users fill the _Search keyword_ field
      -  User optionally selects a _Campaign_
      -  User clicks on _Search_ button
      -  SMS list is loaded
    - **Exception Flow**
      -  _Date to_ field is prior to _Date from_ field

8. Transaction Report

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Date from_ field
      -  User fills the _Date to_ field
      -  Users fill the _Search keyword_ field
      -  User optionally selects a _Campaign_
      -  User clicks on _Search_ button
      -  Transaction history is loaded
    - **Exception Flow**
      -  _Date to_ field is prior to _Date from_ field

9. Create A Group

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Group name_ field
      -  User presses the _Create_ button
      -  A new group is created and appeared in the group list
    - **Exception Flow**
      -  _Group name_ field is empty

10. Search Group

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Search_ field
      -  User presses _Search_ button
      -  Corresponding group list is loaded

11. Edit A Group

    - **Primary Actor:** User
    - **Normal Flow**
      -  From the group list user presses an _Edit_ button
      -  A pop up is shown
      -  User edits the _Group name field_
      -  User presses the _Update_ button
      -  Group name is updated and appeared in the group list
    - **Exception Flow**
      -  _Group name_ field is empty

12. Delete a group

    - **Primary Actor:** User
    - **Normal Flow**
      -  From the group list user presses a _Delete_ button
      -  A pop up is shown for confirmation
      -  User presses the _Delete_ button
      -  Group is deleted and removed from the group list
    - **Exception Flow**
      -  In the pop up, user presses _Cancel_ button

13. Add contact

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills the _Contact name_ field
      -  User fills the _Contact number_ field
      -  User optionally selects a _Group_
      -  User clicks on _Create_ button
      -  A new contact is created
      -  Contact list page is shown
    - **Exception Flow**
      -  _Contact name_ field isempty
      -  _Contact number_ field is empty

14. Import contact from Excel sheet

    - **Primary Actor:** User
    - **Normal Flow**
      -  User uploads an Excel sheet
      -  User selects the sheet
      -  User selects the Contact _name_ column
      -  User selects the _Contact number_ column
      -  User optionally selects a group
      -  User clicks on _Import_ button
      -  Contacts are created
    - **Exception Flow**
      -  Excel sheet corrupted
      -  Excel sheet ill-formatted

15. Search contacts

    - **Primary Actor:** User
    - **Normal Flow**
      -  User fills _Search_ field
      -  User optionally selects a _Group_
      -  User clicks on _Search_ button
      -  Relevant contact list is loaded
    - **Exception Flow**
      -  _Search_ field is empty

16. Edit contact

    - **Primary Actor:** User
    - **Normal Flow**
      -  From the contact list user presses an _Edit_ button
      -  A pop up is shown
      -  User edits the _Contact name_ field
      -  User edits the _Contact number_ field
      -  User presses the _Update_ button
      -  Contact is updated and appeared in the contact list
    - **Exception Flow**
      -  _Contact name_ field is empty
      -  _Contact number_ field is empty

17. Delete a contact

    - **Primary Actor:** User
    - **Normal Flow**
      -  From the contact list user presses a _Delete_ button
      -  A pop up is shown for confirmation
      -  User presses the _Delete_ button
      -  Contact is deleted and removed from the contact list
    - **Exception Flow**
      -  In the pop up, user presses _Cancel_ button

18. Update Personal Information

- **Primary Actor:** User
- **Normal Flow**
  -  User updates the necessary fields
  -  User presses _Save_ button
  -  User information are updated


## Conclusion

From the discussed analysis, an abstract overview of the system is obtained. It will help to understand the goal purely and point out the harder jobs and challenges. It will also ease selecting test cases in the testing phase.
