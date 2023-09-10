import IndexIconPicker from './components/IndexIconPicker'
import DetailIconPicker from './components/DetailIconPicker'
import FormIconPicker from './components/FormIconPicker'

Nova.booting((app, store) => {
  app.component('index-icon-picker', IndexIconPicker)
  app.component('detail-icon-picker', DetailIconPicker)
  app.component('form-icon-picker', FormIconPicker)
})
