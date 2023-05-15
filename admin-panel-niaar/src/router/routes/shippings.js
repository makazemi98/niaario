export default [
  {
    path: "/shippings",
    name: "shippings",
    component: () => import("@/views/shippings/index.vue"),
  },
  {
    path: '/shippings/create',
    name: 'shippings-create',
    component: () => import('@/views/shippings/create.vue'),
  },
  {
    path: '/shippings/:id',
    name: 'shippings-edit',
    props:true,
    component: () => import('@/views/shippings/edit.vue'),
  },
];
