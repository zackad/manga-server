import { h } from 'preact'

import { LabelItem } from 'App/Components/LabelItem'
import { LinkItem } from 'App/Components/LinkItem'

function Breadcrumbs() {
  const location = decodeURIComponent(document.location.pathname)

  if ('/' === location) {
    return null
  }

  let items = location.split('/').filter((item) => item !== '')
  let breadcrumbs = []
  let link = ''
  for (let item of items) {
    link += '/' + item
    breadcrumbs.push({
      path: encodeURIComponent(link.replace(/^\/+|\/+$/g, '')),
      title: decodeURIComponent(item),
    })
  }

  breadcrumbs.map((item, i, arr) => {
    if (arr.length - 1 === i) {
      item.element = <LabelItem title={item?.title} />
    } else {
      item.element = <LinkItem path={item.path} title={item?.title} />
    }
    return item
  })

  return (
    <div className='p-2 border-b border-gray-500 w-full'>
      <div className='flex overflow-x-scroll text-sm'>{breadcrumbs.map((item) => item.element)}</div>
    </div>
  )
}

export { Breadcrumbs }
