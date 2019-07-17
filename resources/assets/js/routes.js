import Contacts from './pages/Contacts';
import FormContact from './pages/FormContact';
import DeleteContact from './pages/DeleteContact';

export const routes = [
    {
        path: '/',
        name: 'contacts',
        component: Contacts,
        children: [
            {
                path: 'new',
                name: 'contacts.new',
                component: FormContact,
            }, {
                path: 'edit/:id',
                name: 'contacts.edit',
                props: true,
                component: FormContact,
            }, {
                path: 'delete/:id',
                name: 'contacts.delete',
                props: true,
                component: DeleteContact,
            }
        ]
    }, {
        path: '*',
        redirect: '/'
    }
];
