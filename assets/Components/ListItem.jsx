import { h } from 'preact'

function ListItem({ className, item }) {
  return (
    <p className='p-2 border-b'>
      <a className={`${className} no-underline`} href={item.uri}>
        {item.label}
      </a>
    </p>
  )
}

export { ListItem }
