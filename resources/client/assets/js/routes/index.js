import Vue from 'vue'
import VueRouter from 'vue-router'

import ChangePassword from '../components/ChangePassword.vue'
import SurveysIndex from '../components/cruds/Surveys/Index.vue'
import SurveysCreate from '../components/cruds/Surveys/Create.vue'
import SurveysShow from '../components/cruds/Surveys/Show.vue'
import SurveysEdit from '../components/cruds/Surveys/Edit.vue'
import ClassesIndex from '../components/cruds/Classes/Index.vue'
import ClassesCreate from '../components/cruds/Classes/Create.vue'
import ClassesShow from '../components/cruds/Classes/Show.vue'
import ClassesEdit from '../components/cruds/Classes/Edit.vue'
import InstitutionsIndex from '../components/cruds/Institutions/Index.vue'
import InstitutionsCreate from '../components/cruds/Institutions/Create.vue'
import InstitutionsShow from '../components/cruds/Institutions/Show.vue'
import InstitutionsEdit from '../components/cruds/Institutions/Edit.vue'
import CategoriesIndex from '../components/cruds/Categories/Index.vue'
import CategoriesCreate from '../components/cruds/Categories/Create.vue'
import CategoriesShow from '../components/cruds/Categories/Show.vue'
import CategoriesEdit from '../components/cruds/Categories/Edit.vue'
import PermissionsIndex from '../components/cruds/Permissions/Index.vue'
import PermissionsCreate from '../components/cruds/Permissions/Create.vue'
import PermissionsShow from '../components/cruds/Permissions/Show.vue'
import PermissionsEdit from '../components/cruds/Permissions/Edit.vue'
import RolesIndex from '../components/cruds/Roles/Index.vue'
import RolesCreate from '../components/cruds/Roles/Create.vue'
import RolesShow from '../components/cruds/Roles/Show.vue'
import RolesEdit from '../components/cruds/Roles/Edit.vue'
import UsersIndex from '../components/cruds/Users/Index.vue'
import UsersCreate from '../components/cruds/Users/Create.vue'
import UsersShow from '../components/cruds/Users/Show.vue'
import UsersEdit from '../components/cruds/Users/Edit.vue'

Vue.use(VueRouter)

const routes = [
    { path: '/change-password', component: ChangePassword, name: 'auth.change_password' },
    { path: '/surveys', component: SurveysIndex, name: 'surveys.index' },
    { path: '/surveys/create', component: SurveysCreate, name: 'surveys.create' },
    { path: '/surveys/:id', component: SurveysShow, name: 'surveys.show' },
    { path: '/surveys/:id/edit', component: SurveysEdit, name: 'surveys.edit' },
    { path: '/classes', component: ClassesIndex, name: 'classes.index' },
    { path: '/classes/create', component: ClassesCreate, name: 'classes.create' },
    { path: '/classes/:id', component: ClassesShow, name: 'classes.show' },
    { path: '/classes/:id/edit', component: ClassesEdit, name: 'classes.edit' },
    { path: '/institutions', component: InstitutionsIndex, name: 'institutions.index' },
    { path: '/institutions/create', component: InstitutionsCreate, name: 'institutions.create' },
    { path: '/institutions/:id', component: InstitutionsShow, name: 'institutions.show' },
    { path: '/institutions/:id/edit', component: InstitutionsEdit, name: 'institutions.edit' },
    { path: '/categories', component: CategoriesIndex, name: 'categories.index' },
    { path: '/categories/create', component: CategoriesCreate, name: 'categories.create' },
    { path: '/categories/:id', component: CategoriesShow, name: 'categories.show' },
    { path: '/categories/:id/edit', component: CategoriesEdit, name: 'categories.edit' },
    { path: '/permissions', component: PermissionsIndex, name: 'permissions.index' },
    { path: '/permissions/create', component: PermissionsCreate, name: 'permissions.create' },
    { path: '/permissions/:id', component: PermissionsShow, name: 'permissions.show' },
    { path: '/permissions/:id/edit', component: PermissionsEdit, name: 'permissions.edit' },
    { path: '/roles', component: RolesIndex, name: 'roles.index' },
    { path: '/roles/create', component: RolesCreate, name: 'roles.create' },
    { path: '/roles/:id', component: RolesShow, name: 'roles.show' },
    { path: '/roles/:id/edit', component: RolesEdit, name: 'roles.edit' },
    { path: '/users', component: UsersIndex, name: 'users.index' },
    { path: '/users/create', component: UsersCreate, name: 'users.create' },
    { path: '/users/:id', component: UsersShow, name: 'users.show' },
    { path: '/users/:id/edit', component: UsersEdit, name: 'users.edit' },
]

export default new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
})
