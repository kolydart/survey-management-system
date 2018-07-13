import Vue from 'vue'
import VueRouter from 'vue-router'

import ChangePassword from '../components/ChangePassword.vue'
import SurveysIndex from '../components/cruds/Surveys/Index.vue'
import SurveysCreate from '../components/cruds/Surveys/Create.vue'
import SurveysShow from '../components/cruds/Surveys/Show.vue'
import SurveysEdit from '../components/cruds/Surveys/Edit.vue'
import QuestionnairesIndex from '../components/cruds/Questionnaires/Index.vue'
import QuestionnairesCreate from '../components/cruds/Questionnaires/Create.vue'
import QuestionnairesShow from '../components/cruds/Questionnaires/Show.vue'
import QuestionnairesEdit from '../components/cruds/Questionnaires/Edit.vue'
import ResponsesIndex from '../components/cruds/Responses/Index.vue'
import ResponsesCreate from '../components/cruds/Responses/Create.vue'
import ResponsesShow from '../components/cruds/Responses/Show.vue'
import ResponsesEdit from '../components/cruds/Responses/Edit.vue'
import ItemsIndex from '../components/cruds/Items/Index.vue'
import ItemsCreate from '../components/cruds/Items/Create.vue'
import ItemsShow from '../components/cruds/Items/Show.vue'
import ItemsEdit from '../components/cruds/Items/Edit.vue'
import QuestionsIndex from '../components/cruds/Questions/Index.vue'
import QuestionsCreate from '../components/cruds/Questions/Create.vue'
import QuestionsShow from '../components/cruds/Questions/Show.vue'
import QuestionsEdit from '../components/cruds/Questions/Edit.vue'
import AnswerlistsIndex from '../components/cruds/Answerlists/Index.vue'
import AnswerlistsCreate from '../components/cruds/Answerlists/Create.vue'
import AnswerlistsShow from '../components/cruds/Answerlists/Show.vue'
import AnswerlistsEdit from '../components/cruds/Answerlists/Edit.vue'
import AnswersIndex from '../components/cruds/Answers/Index.vue'
import AnswersCreate from '../components/cruds/Answers/Create.vue'
import AnswersShow from '../components/cruds/Answers/Show.vue'
import AnswersEdit from '../components/cruds/Answers/Edit.vue'
import InstitutionsIndex from '../components/cruds/Institutions/Index.vue'
import InstitutionsCreate from '../components/cruds/Institutions/Create.vue'
import InstitutionsShow from '../components/cruds/Institutions/Show.vue'
import InstitutionsEdit from '../components/cruds/Institutions/Edit.vue'
import GroupsIndex from '../components/cruds/Groups/Index.vue'
import GroupsCreate from '../components/cruds/Groups/Create.vue'
import GroupsShow from '../components/cruds/Groups/Show.vue'
import GroupsEdit from '../components/cruds/Groups/Edit.vue'
import CategoriesIndex from '../components/cruds/Categories/Index.vue'
import CategoriesCreate from '../components/cruds/Categories/Create.vue'
import CategoriesShow from '../components/cruds/Categories/Show.vue'
import CategoriesEdit from '../components/cruds/Categories/Edit.vue'
import UsersIndex from '../components/cruds/Users/Index.vue'
import UsersCreate from '../components/cruds/Users/Create.vue'
import UsersShow from '../components/cruds/Users/Show.vue'
import UsersEdit from '../components/cruds/Users/Edit.vue'
import RolesIndex from '../components/cruds/Roles/Index.vue'
import RolesCreate from '../components/cruds/Roles/Create.vue'
import RolesShow from '../components/cruds/Roles/Show.vue'
import RolesEdit from '../components/cruds/Roles/Edit.vue'
import PermissionsIndex from '../components/cruds/Permissions/Index.vue'
import PermissionsShow from '../components/cruds/Permissions/Show.vue'

Vue.use(VueRouter)

const routes = [
    { path: '/change-password', component: ChangePassword, name: 'auth.change_password' },
    { path: '/surveys', component: SurveysIndex, name: 'surveys.index' },
    { path: '/surveys/create', component: SurveysCreate, name: 'surveys.create' },
    { path: '/surveys/:id', component: SurveysShow, name: 'surveys.show' },
    { path: '/surveys/:id/edit', component: SurveysEdit, name: 'surveys.edit' },
    { path: '/questionnaires', component: QuestionnairesIndex, name: 'questionnaires.index' },
    { path: '/questionnaires/create', component: QuestionnairesCreate, name: 'questionnaires.create' },
    { path: '/questionnaires/:id', component: QuestionnairesShow, name: 'questionnaires.show' },
    { path: '/questionnaires/:id/edit', component: QuestionnairesEdit, name: 'questionnaires.edit' },
    { path: '/responses', component: ResponsesIndex, name: 'responses.index' },
    { path: '/responses/create', component: ResponsesCreate, name: 'responses.create' },
    { path: '/responses/:id', component: ResponsesShow, name: 'responses.show' },
    { path: '/responses/:id/edit', component: ResponsesEdit, name: 'responses.edit' },
    { path: '/items', component: ItemsIndex, name: 'items.index' },
    { path: '/items/create', component: ItemsCreate, name: 'items.create' },
    { path: '/items/:id', component: ItemsShow, name: 'items.show' },
    { path: '/items/:id/edit', component: ItemsEdit, name: 'items.edit' },
    { path: '/questions', component: QuestionsIndex, name: 'questions.index' },
    { path: '/questions/create', component: QuestionsCreate, name: 'questions.create' },
    { path: '/questions/:id', component: QuestionsShow, name: 'questions.show' },
    { path: '/questions/:id/edit', component: QuestionsEdit, name: 'questions.edit' },
    { path: '/answerlists', component: AnswerlistsIndex, name: 'answerlists.index' },
    { path: '/answerlists/create', component: AnswerlistsCreate, name: 'answerlists.create' },
    { path: '/answerlists/:id', component: AnswerlistsShow, name: 'answerlists.show' },
    { path: '/answerlists/:id/edit', component: AnswerlistsEdit, name: 'answerlists.edit' },
    { path: '/answers', component: AnswersIndex, name: 'answers.index' },
    { path: '/answers/create', component: AnswersCreate, name: 'answers.create' },
    { path: '/answers/:id', component: AnswersShow, name: 'answers.show' },
    { path: '/answers/:id/edit', component: AnswersEdit, name: 'answers.edit' },
    { path: '/institutions', component: InstitutionsIndex, name: 'institutions.index' },
    { path: '/institutions/create', component: InstitutionsCreate, name: 'institutions.create' },
    { path: '/institutions/:id', component: InstitutionsShow, name: 'institutions.show' },
    { path: '/institutions/:id/edit', component: InstitutionsEdit, name: 'institutions.edit' },
    { path: '/groups', component: GroupsIndex, name: 'groups.index' },
    { path: '/groups/create', component: GroupsCreate, name: 'groups.create' },
    { path: '/groups/:id', component: GroupsShow, name: 'groups.show' },
    { path: '/groups/:id/edit', component: GroupsEdit, name: 'groups.edit' },
    { path: '/categories', component: CategoriesIndex, name: 'categories.index' },
    { path: '/categories/create', component: CategoriesCreate, name: 'categories.create' },
    { path: '/categories/:id', component: CategoriesShow, name: 'categories.show' },
    { path: '/categories/:id/edit', component: CategoriesEdit, name: 'categories.edit' },
    { path: '/users', component: UsersIndex, name: 'users.index' },
    { path: '/users/create', component: UsersCreate, name: 'users.create' },
    { path: '/users/:id', component: UsersShow, name: 'users.show' },
    { path: '/users/:id/edit', component: UsersEdit, name: 'users.edit' },
    { path: '/roles', component: RolesIndex, name: 'roles.index' },
    { path: '/roles/create', component: RolesCreate, name: 'roles.create' },
    { path: '/roles/:id', component: RolesShow, name: 'roles.show' },
    { path: '/roles/:id/edit', component: RolesEdit, name: 'roles.edit' },
    { path: '/permissions', component: PermissionsIndex, name: 'permissions.index' },
    { path: '/permissions/:id', component: PermissionsShow, name: 'permissions.show' },
]

export default new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
})
