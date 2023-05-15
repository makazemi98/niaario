/*

NOTE: tag is not supported in horizontal menu

Array of object

Top level object can be:
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- nav Grp (top level grp is called header group) ---/

title
icon (if it's on top/second level)
children

/--- nav Item (top level item is called header link) ---/

icon (if it's on top/second level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)

*/
import shippings from "./shippings";
import accountant from "./accountant";
import teamMembers from "./team-members";
import inquiries from "./inquiries";
import suppliers from "./suppliers";
import clients from "./clients";
import notifications from "./notifications";
import dashboard from "./dashboard";

// import users from "./users";
// import shops from "./shops";
// import apps from "./apps";
// import pages from "./pages";
// import chartsAndMaps from "./charts-and-maps";
// import uiElements from "./ui-elements";
// import formAndTable from "./forms-and-table";
// import others from "./others";

// Array of sections
export default [
  ...dashboard,
  ...teamMembers,
  ...inquiries,
  ...suppliers,
  ...shippings,
  ...accountant,
  ...clients,
  ...notifications,
  // ...users,
  // ...shops,
  // ...apps,
  // ...uiElements,
  // ...formAndTable,
  // ...pages,
  // ...chartsAndMaps,
  // ...others,
];
