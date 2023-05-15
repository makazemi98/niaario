export default [
  {
    path: '/team-members',
    name: 'team-members',
    component: () => import('@/views/team-members/index.vue'),
  },
  {
    path: '/team-members/create',
    name: 'team-members-create',
    component: () => import('@/views/team-members/create.vue'),
  },
  {
    path: '/team-members/:id',
    name: 'team-members-edit',
    props:true,
    component: () => import('@/views/team-members/edit.vue'),
  },
]
