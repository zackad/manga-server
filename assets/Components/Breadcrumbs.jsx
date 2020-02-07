import { h } from 'preact'
import { LabelItem } from './LabelItem.jsx'
import { LinkItem } from './LinkItem.jsx'

function Breadcrumbs() {
  const location = document.location.pathname
  let items = location.split('/').filter(item => item !== '')

  let breadcrumbs = [{ path: '/', title: 'Root' }]
  let link = ''
  for (let item of items) {
    link += '/' + item
    breadcrumbs.push({
      path: decodeURIComponent(link),
      title: decodeURIComponent(item),
    })
  }

  breadcrumbs.map((item, i, arr) => {
    if (arr.length - 1 === i) {
      item.element = <LabelItem title={item.title} />
    } else {
      item.element = <LinkItem path={item.path} title={item.title} />
    }
    return item
  })

  return (
    <div className='p-2 border-b bg-gray-800 flex fixed w-full'>
      {breadcrumbs.map(item => item.element)}
      <span className={`flex-grow`} />
    </div>
  )
}

export { Breadcrumbs }
