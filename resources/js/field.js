import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-icon-field', IndexField)
  app.component('detail-icon-field', DetailField)
  app.component('form-icon-field', FormField)
})
