export default [
  {
    path: "/suppliers",
    name: "suppliers",
    component: () => import("@/views/suppliers/index.vue"),
  },
  {
    path: "/suppliers/create",
    name: "suppliers-create",
    component: () => import("@/views/suppliers/create.vue"),
  },
  {
    path: "/suppliers/:id",
    name: "suppliers-edit",
    props: true,
    component: () => import("@/views/suppliers/edit.vue"),
  },
];
