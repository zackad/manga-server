import { h } from 'preact'

function ListItem({ className, item }) {
  return (
    <p className='p-2'>
      <a className={`${className} inline-flex items-center`} href={item.uri}>
        {item.isDirectory && (
          <svg
            aria-hidden='true'
            focusable='false'
            data-prefix='fas'
            data-icon='folder-open'
            className='fill-current text-gray-300 h-5 w-5 mr-2'
            role='img'
            xmlns='http://www.w3.org/2000/svg'
            viewBox='0 0 576 512'
          >
            <path
              fill='currentColor'
              d='M572.694 292.093L500.27 416.248A63.997 63.997 0 0 1 444.989 448H45.025c-18.523 0-30.064-20.093-20.731-36.093l72.424-124.155A64 64 0 0 1 152 256h399.964c18.523 0 30.064 20.093 20.73 36.093zM152 224h328v-48c0-26.51-21.49-48-48-48H272l-64-64H48C21.49 64 0 85.49 0 112v278.046l69.077-118.418C86.214 242.25 117.989 224 152 224z'
            ></path>
          </svg>
        )}
        <span className={!item.isDirectory && 'font-thin'}>{item.label}</span>
      </a>
    </p>
  )
}

export { ListItem }
