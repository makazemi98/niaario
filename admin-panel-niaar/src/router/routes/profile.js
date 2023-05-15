export default [
  {
    path: '/profile/:id',
    name: 'profile',
    props:true,
    
    // component: () => import('@/views/pages/account-setting/AccountSetting.vue'),
    component: () => import('@/views/pages/profile/Profile.vue'),
  },
]