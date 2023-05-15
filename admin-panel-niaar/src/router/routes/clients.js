export default [
  {
    path: "/clients",
    name: "clients",
    component: () => import("@/views/clients/index.vue"),
  },
  {
    path: '/clients/create',
    name: 'clients-create',
    component: () => import('@/views/clients/create.vue'),
  },
  {
    path: '/clients/:id',
    name: 'clients-edit',
    props:true,
    component: () => import('@/views/clients/edit.vue'),
  },
];
