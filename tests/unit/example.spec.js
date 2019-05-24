import { expect } from 'chai'
import { mount } from '@vue/test-utils'
import HelloWorld from '@/components/gifimages.vue'

describe('Компонент Counter', () => {
    const wrapper = mount(HelloWorld, {
        propsData: {
            indx: 6
        }
    })
    expect(wrapper.props().indx).to.be.a('Number');
})