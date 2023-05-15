/*

Array of object

Top level object can be:
1. Header
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- Header ---/

header

/--- nav Grp ---/

title
icon (if it's on top level)
tag
tagVariant
children

/--- nav Item ---/

icon (if it's on top level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)
tag
tagVariant

*/
import dashboard from "./dashboard";
import shippings from "./shippings";
import accountant from "./accountant";
import teamMembers from "./team-members";
import inquiries from "./inquiries";
import suppliers from "./suppliers";
import clients from "./clients";
import notifications from "./notifications";

// import users from "./users";
// import shops from "./shops";
// import dashboard from "./dashboard";
// import apps from "./apps-and-pages";
// import pages from './pages'
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
  // ...dashboard,
  // ...apps,
  // ...uiElements,
  // ...formAndTable,
  // ...chartsAndMaps,
  // ...others,
];
