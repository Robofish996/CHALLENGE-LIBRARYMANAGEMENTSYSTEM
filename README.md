# CHALLENGE-LIBRARYMANAGEMENTSYSTEM
Brief:
You are required to build a basic inventory management system for the local library. The new system will help librarians and members of the library, to find books online without having to physically be at the library. Librarians should be able to manage books in the system, view all members, see how many books a member has rented out at a time, and all the books that are currently rented out.



Challenge:

 

Librarian Section

 

Users with a role of librarian, should be able to perform CRUD operations, on all books, in the system. All CRUD operations, should be methods inside the Librarian class.
There should be a tab, where librarians can view a list of all members, registered on the system. If a librarian selected a member, they should be able to view all the books rented by that member, and the status of those books.
Only librarians are able to add new librarians to the system.
Librarians are able to blacklist / suspend the account, of any member whose rental is outstanding, by more than a week.
 

Member

New members should be allowed to register on the system, and existing members must login, before they are allowed access to the system.
The dashboard of all members, should display a list of all rentals that the member currently has, along with their return dates.
The books tab should load in all books, from the database and display them, so that members may browse through the books in the system and eventually select one they wish to rent.
Unavailable books and books that are already rented out should be filtered out of the list and should NOT be displayed.
Members should have a limit of five rentals, meaning that they should not be allowed to rent more than five books at a single time.




