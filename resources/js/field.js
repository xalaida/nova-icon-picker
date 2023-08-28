import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-icon-nova-field', IndexField)
  app.component('detail-icon-nova-field', DetailField)
  app.component('form-icon-nova-field', FormField)
})
