import { h } from 'preact'
import { useEffect, useState } from 'preact/hooks'
import { LabelItem } from './LabelItem.jsx'
import { LinkItem } from './LinkItem.jsx'

function throttle(fn, wait) {
  let time = Date.now()
  return () => {
    if (time + wait - Date.now() < 0) {
      fn()
      time = Date.now()
    }
  }
}

function Breadcrumbs(props) {
  const [pinned, setPinned] = useState(true)
  const location = document.location.pathname
  let items = location.split('/').filter(item => item !== '')

  useEffect(() => {
    const breadcrumbsHeight = 40
    let pxTrigger = 0

    const scrollHandler = () => {
      const pxFromTop = window.pageYOffset || window.scrollY

      if (pxFromTop > breadcrumbsHeight) {
        setPinned(pxFromTop < pxTrigger)
        pxTrigger = pxFromTop
      } else {
        setPinned(true)
      }
    }

    document.addEventListener('scroll', throttle(scrollHandler, 300))
  }, [])

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

  const pinnedStyle = pinned ? 'mt-o' : '-mt-12'

  return (
    <div className={`${pinnedStyle} p-2 border-b bg-gray-800 flex fixed w-full`}>
      <div className='flex overflow-x-scroll'>{breadcrumbs.map(item => item.element)}</div>
      <span className={`flex-grow`} />
      {props.toggleReader}
    </div>
  )
}

export { Breadcrumbs }
