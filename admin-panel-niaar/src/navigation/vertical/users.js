export default [
    {
        header: "PAGES",
    },
    {
        title: "Users",
        icon: "UsersIcon",
        children: [ 
            {
                title: "Admin Users List",
                route: "admin-users-index",
            },
            {
                title: "Messages",
                route: "messages-index",
            },
            {
                title: "Seller Approval Form",
                route: "seller-approval-form-index",
            },
            {
                title: "Seller Approval Requests",
                route: "seller-approval-requests-index",
            },
            {
                title: "User Gpdr Reuqests",
                route: "user-gpdr-requests-index",
            },
        ],
    },
];
